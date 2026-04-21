'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    // ── Vista: carga de trabajo por supervisor ──────────────────────────────
    await q(`DROP VIEW IF EXISTS v_carga_supervisores`);
    await q(`
      CREATE VIEW v_carga_supervisores AS
      SELECT
        u.id                                      AS supervisor_id,
        u.nombre                                  AS nombre,
        u.apellidos                               AS apellidos,
        u.alias                                   AS alias,
        u.email                                   AS email,
        COUNT(s.id)                               AS total_siniestros,
        SUM(CASE WHEN ces.es_terminal = 0 OR ces.es_terminal IS NULL
                 THEN 1 ELSE 0 END)               AS siniestros_activos
      FROM usuarios u
      INNER JOIN perfiles_empleados pe ON pe.usuario_id = u.id
      LEFT  JOIN siniestros s          ON s.supervisor_id = u.id
      LEFT  JOIN catalogo_estatus_siniestros ces ON ces.id = s.estatus_id
      WHERE u.rol_id = 3
      GROUP BY u.id, u.nombre, u.apellidos, u.alias, u.email
    `);

    // ── Trigger: asignación automática de supervisor al crear siniestro ─────
    await q(`DROP TRIGGER IF EXISTS tr_asignar_supervisor`);
    await q(`
      CREATE TRIGGER tr_asignar_supervisor
      BEFORE INSERT ON siniestros
      FOR EACH ROW
      BEGIN
        DECLARE v_supervisor_id INT DEFAULT NULL;

        -- Supervisor con menor cantidad de siniestros activos (no terminales).
        -- En caso de empate se elige el de menor id como pivote estable.
        SELECT u.id
          INTO v_supervisor_id
          FROM usuarios u
          INNER JOIN perfiles_empleados pe ON pe.usuario_id = u.id
          LEFT  JOIN siniestros s
                ON s.supervisor_id = u.id
                AND EXISTS (
                      SELECT 1
                      FROM catalogo_estatus_siniestros ces
                      WHERE ces.id = s.estatus_id
                        AND (ces.es_terminal = 0 OR ces.es_terminal IS NULL)
                    )
          WHERE u.rol_id = 3
          GROUP BY u.id
          ORDER BY COUNT(s.id) ASC, u.id ASC
          LIMIT 1;

        SET NEW.supervisor_id = v_supervisor_id;
      END
    `);
  },

  async down(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    await q(`DROP TRIGGER IF EXISTS tr_asignar_supervisor`);
    await q(`DROP VIEW IF EXISTS v_carga_supervisores`);
  }
};
