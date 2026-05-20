'use strict';

/**
 * Views #5 y #6 de los 8 requeridos:
 *   v_polizas_activas   — usa fn_es_poliza_activa, actualiza sp_get_polizas_by_usuario
 *   v_terceros_por_siniestro — terceros con contexto del siniestro y asegurado,
 *                              actualiza sp_get_terceros_siniestro
 */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    // ── VIEW 5: pólizas con vigencia calculada por función ───────────────────
    await q(`DROP VIEW IF EXISTS v_polizas_activas`);
    await q(`
      CREATE VIEW v_polizas_activas AS
      SELECT
        p.id,
        p.usuario_id,
        p.numero_poliza,
        p.placas,
        p.fecha_inicio,
        p.fecha_fin,
        fn_es_poliza_activa(p.fecha_fin)  AS activa,
        p.valor_asegurado,
        v.marca,
        v.modelo,
        v.anio,
        v.version,
        v.tipo_vehiculo,
        seg.id                            AS seguro_id,
        seg.nombre_seguro,
        seg.nivel,
        seg.deducible_porcentaje,
        c.nombre_comercial                AS compania
      FROM polizas p
      INNER JOIN vehiculos         v   ON v.id  = p.catalogo_vehiculo_id
      INNER JOIN seguros           seg ON seg.id = p.seguro_id
      INNER JOIN companias_seguros c   ON c.id  = seg.compania_id
    `);

    // SP actualizado para usar el view
    await q(`DROP PROCEDURE IF EXISTS sp_get_polizas_by_usuario`);
    await q(`
      CREATE PROCEDURE sp_get_polizas_by_usuario(IN p_usuario_id INT)
      BEGIN
        SELECT
          id,
          numero_poliza,
          placas,
          fecha_inicio,
          fecha_fin,
          activa         AS estatus_poliza,
          valor_asegurado,
          marca,
          modelo,
          anio,
          version,
          tipo_vehiculo,
          nombre_seguro,
          nivel,
          deducible_porcentaje,
          compania
        FROM v_polizas_activas
        WHERE usuario_id = p_usuario_id
        ORDER BY fecha_inicio DESC;
      END
    `);

    // ── VIEW 6: terceros involucrados con contexto del siniestro ─────────────
    await q(`DROP VIEW IF EXISTS v_terceros_por_siniestro`);
    await q(`
      CREATE VIEW v_terceros_por_siniestro AS
      SELECT
        t.id,
        t.siniestro_id,
        t.marca_tercero,
        t.modelo_tercero,
        t.placas_tercero,
        t.aseguradora_tercero,
        t.descripcion_danos,
        s.numero_reporte,
        p.placas                                      AS placas_asegurado,
        CONCAT(u.nombre, ' ', u.apellidos)            AS duenio_nombre
      FROM terceros_involucrados t
      INNER JOIN siniestros s ON s.id = t.siniestro_id
      INNER JOIN polizas    p ON p.id = s.poliza_id
      INNER JOIN usuarios   u ON u.id = p.usuario_id
    `);

    // SP actualizado para usar el view
    await q(`DROP PROCEDURE IF EXISTS sp_get_terceros_siniestro`);
    await q(`
      CREATE PROCEDURE sp_get_terceros_siniestro(IN p_id INT)
      BEGIN
        SELECT
          id,
          siniestro_id,
          marca_tercero,
          modelo_tercero,
          placas_tercero,
          aseguradora_tercero,
          descripcion_danos,
          numero_reporte,
          placas_asegurado,
          duenio_nombre
        FROM v_terceros_por_siniestro
        WHERE siniestro_id = p_id;
      END
    `);
  },

  async down(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    await q(`DROP PROCEDURE IF EXISTS sp_get_terceros_siniestro`);
    await q(`DROP PROCEDURE IF EXISTS sp_get_polizas_by_usuario`);
    await q(`DROP VIEW IF EXISTS v_terceros_por_siniestro`);
    await q(`DROP VIEW IF EXISTS v_polizas_activas`);
  }
};
