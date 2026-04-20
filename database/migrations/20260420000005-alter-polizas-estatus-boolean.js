'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    // Primero convertir los strings a 1/0 mientras la columna sigue siendo VARCHAR
    await q(`UPDATE polizas SET estatus_poliza = '1'`);

    // Ahora sí cambiar el tipo (todos los valores ya son '1' o '0', convertibles)
    await q(`
      ALTER TABLE polizas
        MODIFY COLUMN estatus_poliza TINYINT(1) NOT NULL DEFAULT 1
    `);

    // Recrear sp_contratar_poliza con TRUE en lugar de 'Vigente'
    await q(`DROP PROCEDURE IF EXISTS sp_contratar_poliza`);
    await q(`
      CREATE PROCEDURE sp_contratar_poliza(
        IN p_usuario_id           INT,
        IN p_seguro_id            INT,
        IN p_catalogo_vehiculo_id INT,
        IN p_placas               VARCHAR(20),
        IN p_fecha_inicio         DATE,
        IN p_fecha_fin            DATE
      )
      BEGIN
        INSERT INTO polizas (
          usuario_id, seguro_id, numero_poliza, placas,
          fecha_inicio, fecha_fin, estatus_poliza, catalogo_vehiculo_id
        )
        VALUES (
          p_usuario_id,
          p_seguro_id,
          CONCAT('POL-', YEAR(NOW()), '-', LPAD(FLOOR(RAND() * 999999), 6, '0')),
          p_placas,
          p_fecha_inicio,
          p_fecha_fin,
          TRUE,
          p_catalogo_vehiculo_id
        );

        SELECT LAST_INSERT_ID() AS id;
      END
    `);

    // Recrear sp_get_poliza_by_numero filtrando por TRUE
    await q(`DROP PROCEDURE IF EXISTS sp_get_poliza_by_numero`);
    await q(`
      CREATE PROCEDURE sp_get_poliza_by_numero(IN p_numero VARCHAR(50))
      BEGIN
        SELECT
          p.id                    AS poliza_id,
          p.numero_poliza,
          p.placas,
          p.estatus_poliza,
          p.fecha_inicio,
          p.fecha_fin,
          u.id                    AS usuario_id,
          CONCAT(u.nombre, ' ', u.apellidos) AS nombre_duenio,
          v.marca,
          v.modelo,
          v.anio,
          v.tipo_vehiculo,
          s.suma_asegurada,
          s.deducible_porcentaje,
          cs.nombre_comercial     AS compania
        FROM polizas p
        INNER JOIN usuarios u          ON u.id = p.usuario_id
        INNER JOIN vehiculos v          ON v.id = p.catalogo_vehiculo_id
        INNER JOIN seguros s            ON s.id = p.seguro_id
        INNER JOIN companias_seguros cs ON cs.id = s.compania_id
        WHERE p.numero_poliza = p_numero
          AND p.estatus_poliza = TRUE
        LIMIT 1;
      END
    `);
  },

  async down(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    await q(`
      ALTER TABLE polizas
        MODIFY COLUMN estatus_poliza VARCHAR(30) NOT NULL DEFAULT 'Vigente'
    `);

    await q(`DROP PROCEDURE IF EXISTS sp_contratar_poliza`);
    await q(`DROP PROCEDURE IF EXISTS sp_get_poliza_by_numero`);
  }
};
