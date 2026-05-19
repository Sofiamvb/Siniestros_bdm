'use strict';

/**
 * Cubre requisitos de entrega:
 *  - 2 Funciones: fn_en_rango_fecha, fn_es_poliza_activa
 *  - 1 Trigger adicional: tr_crear_chat_siniestro  (total 2 triggers)
 *  - 2 Views nuevas: v_siniestros_ajustador, v_siniestros_supervisor
 *  - SPs actualizados para filtrar por fecha usando la función y el view
 */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    // ── FUNCIÓN 1: rango de fechas opcional ──────────────────────────────────
    // Retorna 1 si p_fecha está dentro del rango. NULL en los extremos = sin límite.
    await q(`DROP FUNCTION IF EXISTS fn_en_rango_fecha`);
    await q(`
      CREATE FUNCTION fn_en_rango_fecha(
        p_fecha  DATETIME,
        p_inicio DATE,
        p_fin    DATE
      ) RETURNS TINYINT(1)
      DETERMINISTIC
      BEGIN
        RETURN (
          (p_inicio IS NULL OR DATE(p_fecha) >= p_inicio) AND
          (p_fin    IS NULL OR DATE(p_fecha) <= p_fin)
        );
      END
    `);

    // ── FUNCIÓN 2: determina si una póliza sigue vigente ────────────────────
    await q(`DROP FUNCTION IF EXISTS fn_es_poliza_activa`);
    await q(`
      CREATE FUNCTION fn_es_poliza_activa(p_fecha_fin DATE) RETURNS TINYINT(1)
      DETERMINISTIC
      BEGIN
        RETURN p_fecha_fin >= CURDATE();
      END
    `);

    // ── TRIGGER 2: crea el chat automáticamente al registrar un siniestro ───
    await q(`DROP TRIGGER IF EXISTS tr_crear_chat_siniestro`);
    await q(`
      CREATE TRIGGER tr_crear_chat_siniestro
      AFTER INSERT ON siniestros
      FOR EACH ROW
      BEGIN
        INSERT IGNORE INTO chats (siniestro_id) VALUES (NEW.id);
      END
    `);

    // ── VIEW: datos completos de siniestros para ajustadores ────────────────
    await q(`DROP VIEW IF EXISTS v_siniestros_ajustador`);
    await q(`
      CREATE VIEW v_siniestros_ajustador AS
      SELECT
        s.id,
        s.ajustador_id,
        s.supervisor_id,
        s.numero_reporte,
        s.fecha_hora_siniestro,
        s.latitud,
        s.longitud,
        s.conductor_momento,
        s.descripcion_hechos,
        s.dictamen_supervisor,
        s.presupuesto_reparacion,
        s.perdida_total,
        ces.id                                        AS estatus_id,
        ces.descripcion_interna                       AS estatus,
        ces.color_ui                                  AS estatus_color,
        CONCAT(duenio.nombre, ' ', duenio.apellidos)  AS duenio_nombre,
        duenio.email                                  AS duenio_email,
        v.marca,
        v.modelo,
        v.anio,
        p.placas,
        p.numero_poliza,
        p.fecha_inicio,
        p.fecha_fin,
        p.usuario_id,
        cs.nombre_comercial                           AS compania,
        CONCAT(aj.nombre, ' ', aj.apellidos)          AS ajustador_nombre,
        (SELECT e.archivo_multimedia
         FROM evidencias_siniestro e
         WHERE e.siniestro_id = s.id
         ORDER BY e.fecha_subida ASC LIMIT 1)         AS primera_evidencia,
        (SELECT e.tipo_mime
         FROM evidencias_siniestro e
         WHERE e.siniestro_id = s.id
         ORDER BY e.fecha_subida ASC LIMIT 1)         AS primera_evidencia_mime
      FROM siniestros s
      INNER JOIN polizas p                        ON p.id  = s.poliza_id
      INNER JOIN usuarios duenio                  ON duenio.id = p.usuario_id
      INNER JOIN vehiculos v                      ON v.id  = p.catalogo_vehiculo_id
      INNER JOIN seguros seg                      ON seg.id = p.seguro_id
      INNER JOIN companias_seguros cs             ON cs.id  = seg.compania_id
      INNER JOIN catalogo_estatus_siniestros ces  ON ces.id = s.estatus_id
      INNER JOIN usuarios aj                      ON aj.id  = s.ajustador_id
    `);

    // ── VIEW: datos completos de siniestros para supervisores ───────────────
    await q(`DROP VIEW IF EXISTS v_siniestros_supervisor`);
    await q(`
      CREATE VIEW v_siniestros_supervisor AS
      SELECT
        s.id,
        s.ajustador_id,
        s.supervisor_id,
        s.numero_reporte,
        s.fecha_hora_siniestro,
        s.latitud,
        s.longitud,
        s.conductor_momento,
        s.descripcion_hechos,
        s.dictamen_supervisor,
        s.presupuesto_reparacion,
        s.perdida_total,
        ces.id                                        AS estatus_id,
        ces.descripcion_interna                       AS estatus,
        ces.color_ui                                  AS estatus_color,
        CONCAT(duenio.nombre, ' ', duenio.apellidos)  AS duenio_nombre,
        duenio.email                                  AS duenio_email,
        v.marca,
        v.modelo,
        v.anio,
        p.placas,
        p.numero_poliza,
        p.fecha_inicio,
        p.fecha_fin,
        p.usuario_id,
        cs.nombre_comercial                           AS compania,
        CONCAT(aj.nombre,  ' ', aj.apellidos)         AS ajustador_nombre,
        CONCAT(sup.nombre, ' ', sup.apellidos)        AS supervisor_nombre,
        (SELECT e.archivo_multimedia
         FROM evidencias_siniestro e
         WHERE e.siniestro_id = s.id
         ORDER BY e.fecha_subida ASC LIMIT 1)         AS primera_evidencia,
        (SELECT e.tipo_mime
         FROM evidencias_siniestro e
         WHERE e.siniestro_id = s.id
         ORDER BY e.fecha_subida ASC LIMIT 1)         AS primera_evidencia_mime
      FROM siniestros s
      INNER JOIN polizas p                        ON p.id  = s.poliza_id
      INNER JOIN usuarios duenio                  ON duenio.id = p.usuario_id
      INNER JOIN vehiculos v                      ON v.id  = p.catalogo_vehiculo_id
      INNER JOIN seguros seg                      ON seg.id = p.seguro_id
      INNER JOIN companias_seguros cs             ON cs.id  = seg.compania_id
      INNER JOIN catalogo_estatus_siniestros ces  ON ces.id = s.estatus_id
      INNER JOIN usuarios aj                      ON aj.id  = s.ajustador_id
      LEFT  JOIN usuarios sup                     ON sup.id = s.supervisor_id
    `);

    // ── SP ajustador actualizado: usa el view + fn_en_rango_fecha ───────────
    await q(`DROP PROCEDURE IF EXISTS sp_get_siniestros_ajustador`);
    await q(`
      CREATE PROCEDURE sp_get_siniestros_ajustador(
        IN p_ajustador_id INT,
        IN p_inicio       DATE,
        IN p_fin          DATE
      )
      BEGIN
        SELECT
          id, ajustador_id, supervisor_id, numero_reporte,
          fecha_hora_siniestro, latitud, longitud, conductor_momento,
          descripcion_hechos, dictamen_supervisor, presupuesto_reparacion, perdida_total,
          estatus_id, estatus, estatus_color,
          duenio_nombre, duenio_email,
          marca, modelo, anio, placas, numero_poliza,
          fecha_inicio, fecha_fin, usuario_id, compania, ajustador_nombre,
          primera_evidencia, primera_evidencia_mime
        FROM v_siniestros_ajustador
        WHERE ajustador_id = p_ajustador_id
          AND fn_en_rango_fecha(fecha_hora_siniestro, p_inicio, p_fin) = 1
        ORDER BY id DESC;
      END
    `);

    // ── SP supervisor actualizado: usa el view + fn_en_rango_fecha ──────────
    await q(`DROP PROCEDURE IF EXISTS sp_get_siniestros_supervisor`);
    await q(`
      CREATE PROCEDURE sp_get_siniestros_supervisor(
        IN p_inicio DATE,
        IN p_fin    DATE
      )
      BEGIN
        SELECT
          id, ajustador_id, supervisor_id, numero_reporte,
          fecha_hora_siniestro, latitud, longitud, conductor_momento,
          descripcion_hechos, dictamen_supervisor, presupuesto_reparacion, perdida_total,
          estatus_id, estatus, estatus_color,
          duenio_nombre, duenio_email,
          marca, modelo, anio, placas, numero_poliza,
          fecha_inicio, fecha_fin, usuario_id, compania,
          ajustador_nombre, supervisor_nombre,
          primera_evidencia, primera_evidencia_mime
        FROM v_siniestros_supervisor
        WHERE fn_en_rango_fecha(fecha_hora_siniestro, p_inicio, p_fin) = 1
        ORDER BY id DESC;
      END
    `);
  },

  async down(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    await q(`DROP PROCEDURE IF EXISTS sp_get_siniestros_supervisor`);
    await q(`DROP PROCEDURE IF EXISTS sp_get_siniestros_ajustador`);
    await q(`DROP VIEW IF EXISTS v_siniestros_supervisor`);
    await q(`DROP VIEW IF EXISTS v_siniestros_ajustador`);
    await q(`DROP TRIGGER IF EXISTS tr_crear_chat_siniestro`);
    await q(`DROP FUNCTION IF EXISTS fn_es_poliza_activa`);
    await q(`DROP FUNCTION IF EXISTS fn_en_rango_fecha`);
  }
};
