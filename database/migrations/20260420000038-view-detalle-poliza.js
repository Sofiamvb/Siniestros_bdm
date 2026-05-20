'use strict';

/**
 * View #7/8: v_detalle_poliza
 * Consolida todos los datos de una póliza: vehículo, seguro, compañía y asegurado.
 * Usada por sp_get_poliza_detalle para la página de detalle de póliza.
 */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    await q(`DROP VIEW IF EXISTS v_detalle_poliza`);
    await q(`
      CREATE VIEW v_detalle_poliza AS
      SELECT
        p.id                                          AS poliza_id,
        p.usuario_id,
        p.numero_poliza,
        p.placas,
        p.fecha_inicio,
        p.fecha_fin,
        fn_es_poliza_activa(p.fecha_fin)              AS activa,
        p.valor_asegurado,
        v.id                                          AS vehiculo_id,
        v.marca,
        v.modelo,
        v.anio,
        v.version,
        v.tipo_vehiculo,
        v.numero_pasajeros,
        v.cilindros,
        seg.id                                        AS seguro_id,
        seg.nombre_seguro,
        seg.nivel,
        seg.suma_asegurada,
        seg.deducible_porcentaje,
        seg.descripcion_cobertura,
        c.id                                          AS compania_id,
        c.nombre_comercial                            AS compania,
        c.razon_social,
        c.rfc                                         AS compania_rfc,
        c.telefono_cabina,
        CONCAT(u.nombre, ' ', u.apellidos)            AS asegurado_nombre,
        u.email                                       AS asegurado_email,
        (SELECT COUNT(*) FROM siniestros s
         WHERE s.poliza_id = p.id)                    AS total_siniestros
      FROM polizas p
      INNER JOIN vehiculos         v   ON v.id  = p.catalogo_vehiculo_id
      INNER JOIN seguros           seg ON seg.id = p.seguro_id
      INNER JOIN companias_seguros c   ON c.id  = seg.compania_id
      INNER JOIN usuarios          u   ON u.id  = p.usuario_id
    `);

    await q(`DROP PROCEDURE IF EXISTS sp_get_poliza_detalle`);
    await q(`
      CREATE PROCEDURE sp_get_poliza_detalle(
        IN p_poliza_id  INT,
        IN p_usuario_id INT
      )
      BEGIN
        SELECT
          poliza_id, usuario_id, numero_poliza, placas,
          fecha_inicio, fecha_fin, activa, valor_asegurado,
          vehiculo_id, marca, modelo, anio, version,
          tipo_vehiculo, numero_pasajeros, cilindros,
          seguro_id, nombre_seguro, nivel,
          suma_asegurada, deducible_porcentaje, descripcion_cobertura,
          compania_id, compania, razon_social, compania_rfc, telefono_cabina,
          asegurado_nombre, asegurado_email,
          total_siniestros
        FROM v_detalle_poliza
        WHERE poliza_id  = p_poliza_id
          AND usuario_id = p_usuario_id;
      END
    `);
  },

  async down(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);
    await q(`DROP PROCEDURE IF EXISTS sp_get_poliza_detalle`);
    await q(`DROP VIEW IF EXISTS v_detalle_poliza`);
  }
};
