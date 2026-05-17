'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    await queryInterface.sequelize.query(`DROP VIEW IF EXISTS v_carga_supervisores`);
  },

  async down(queryInterface) {
    await queryInterface.sequelize.query(`
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
  }
};
