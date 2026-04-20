'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    // ── Obtener vehículo por id ──────────────────────────────────────────────
    await q(`
      CREATE PROCEDURE sp_get_vehiculo_by_id(IN p_id INT)
      BEGIN
        SELECT id, marca, modelo, anio, version, tipo_vehiculo,
               numero_pasajeros, cilindros, precio_seguro
        FROM vehiculos
        WHERE id = p_id AND status = TRUE
        LIMIT 1;
      END
    `);

    // ── Obtener todos los seguros disponibles (join con compañía) ────────────
    await q(`
      CREATE PROCEDURE sp_get_seguros_disponibles(IN p_vehiculo_id INT)
      BEGIN
        SELECT s.id,
               s.nombre_seguro,
               s.nivel,
               s.suma_asegurada,
               s.deducible_porcentaje,
               s.descripcion_cobertura,
               c.id               AS compania_id,
               c.nombre_comercial AS compania
        FROM seguros s
        JOIN companias_seguros c ON c.id = s.compania_id
        ORDER BY c.nombre_comercial ASC, s.nivel ASC;
      END
    `);

    // ── Obtener un seguro por id (para la página de pago) ───────────────────
    await q(`
      CREATE PROCEDURE sp_get_seguro_by_id(IN p_id INT)
      BEGIN
        SELECT s.id,
               s.nombre_seguro,
               s.nivel,
               s.suma_asegurada,
               s.deducible_porcentaje,
               s.descripcion_cobertura,
               c.id               AS compania_id,
               c.nombre_comercial AS compania
        FROM seguros s
        JOIN companias_seguros c ON c.id = s.compania_id
        WHERE s.id = p_id
        LIMIT 1;
      END
    `);

    // ── Contratar póliza ─────────────────────────────────────────────────────
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
          'Vigente',
          p_catalogo_vehiculo_id
        );

        SELECT LAST_INSERT_ID() AS id;
      END
    `);
  },

  async down(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);
    await q('DROP PROCEDURE IF EXISTS sp_contratar_poliza');
    await q('DROP PROCEDURE IF EXISTS sp_get_seguro_by_id');
    await q('DROP PROCEDURE IF EXISTS sp_get_seguros_disponibles');
    await q('DROP PROCEDURE IF EXISTS sp_get_vehiculo_by_id');
  }
};
