'use strict';

/**
 * Actualiza los comentarios del diccionario de datos para reflejar los cambios
 * realizados en migraciones 20260420000005, 20260420000008, 20260420000009
 * y 20260420000010 que modificaron tipos y nombres de columnas sin actualizar
 * los comentarios usados por la vista `diccionario`.
 */
/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    // ── POLIZAS ────────────────────────────────────────────────────────────
    // estatus_poliza: VARCHAR(255) "activa/vencida/cancelada" → TINYINT(1) booleano
    await q(`
      ALTER TABLE polizas
        MODIFY COLUMN estatus_poliza TINYINT(1) NOT NULL DEFAULT 1
          COMMENT 'Estado de vigencia de la póliza: 1 = activa, 0 = caducada'
    `);

    // ── SINIESTROS ─────────────────────────────────────────────────────────
    // numero_reporte: INT → VARCHAR(30), genera folio tipo SIN-YYYY-XXXXXX
    await q(`
      ALTER TABLE siniestros
        MODIFY COLUMN numero_reporte VARCHAR(30) NOT NULL
          COMMENT 'Folio alfanumérico del reporte generado automáticamente (ej. SIN-2026-000123)'
    `);

    // presupuesto_reparacion: VARCHAR(255) → DECIMAL(12,2)
    await q(`
      ALTER TABLE siniestros
        MODIFY COLUMN presupuesto_reparacion DECIMAL(12,2) NULL
          COMMENT 'Monto estimado en pesos MXN para la reparación; NULL cuando es pérdida total'
    `);

    // dictamen_supervisor: renombrado desde dictamen_ajustador; solo el supervisor emite dictamen
    await q(`
      ALTER TABLE siniestros
        MODIFY COLUMN dictamen_supervisor VARCHAR(255) NULL
          COMMENT 'Dictamen final emitido por el supervisor tras validar el reporte del ajustador'
    `);

    // perdida_total: columna nueva agregada en migración 009; documentar en diccionario
    await q(`
      ALTER TABLE siniestros
        MODIFY COLUMN perdida_total TINYINT(1) NOT NULL DEFAULT 0
          COMMENT 'Indica si el siniestro se clasificó como pérdida total: 1 = sí, 0 = no; cuando es 1 el presupuesto queda en NULL'
    `);
  },

  async down(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    // Revertir comentarios a los valores originales del diccionario
    await q(`
      ALTER TABLE polizas
        MODIFY COLUMN estatus_poliza TINYINT(1) NOT NULL DEFAULT 1
          COMMENT 'Estado actual de la póliza: activa, vencida o cancelada'
    `);

    await q(`
      ALTER TABLE siniestros
        MODIFY COLUMN numero_reporte VARCHAR(30) NOT NULL
          COMMENT 'Folio consecutivo del reporte; se genera automáticamente por trigger'
    `);

    await q(`
      ALTER TABLE siniestros
        MODIFY COLUMN presupuesto_reparacion DECIMAL(12,2) NULL
          COMMENT 'Monto estimado en pesos para la reparación del vehículo siniestrado'
    `);

    await q(`
      ALTER TABLE siniestros
        MODIFY COLUMN dictamen_supervisor VARCHAR(255) NULL
          COMMENT 'Dictamen técnico preliminar emitido por el ajustador tras el levantamiento'
    `);

    await q(`
      ALTER TABLE siniestros
        MODIFY COLUMN perdida_total TINYINT(1) NOT NULL DEFAULT 0
          COMMENT ''
    `);
  }
};
