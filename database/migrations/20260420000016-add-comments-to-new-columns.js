'use strict';

/**
 * Agrega los comentarios del diccionario de datos a columnas que fueron
 * añadidas o modificadas sin COMMENT en migraciones anteriores:
 *  - 20260420000005: polizas.estatus_poliza (cambio VARCHAR → TINYINT sin COMMENT)
 *  - 20260420000008: siniestros.numero_reporte y presupuesto_reparacion (sin COMMENT)
 *  - 20260420000009: siniestros.perdida_total (columna nueva sin COMMENT)
 *  - 20260420000010: siniestros.dictamen_supervisor (renombrado sin COMMENT)
 */
/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    // polizas.estatus_poliza ─ booleano de vigencia
    await q(`
      ALTER TABLE polizas
        MODIFY COLUMN estatus_poliza TINYINT(1) NOT NULL DEFAULT 1
          COMMENT 'Estado de vigencia de la póliza: 1 = activa, 0 = caducada'
    `);

    // siniestros.numero_reporte ─ folio alfanumérico generado por el SP
    await q(`
      ALTER TABLE siniestros
        MODIFY COLUMN numero_reporte VARCHAR(30) NOT NULL
          COMMENT 'Folio alfanumérico generado automáticamente al registrar el siniestro (ej. SIN-2026-000123)'
    `);

    // siniestros.presupuesto_reparacion ─ decimal con presición monetaria
    await q(`
      ALTER TABLE siniestros
        MODIFY COLUMN presupuesto_reparacion DECIMAL(12,2) NULL
          COMMENT 'Monto estimado en pesos MXN para la reparación del vehículo; NULL cuando perdida_total = 1'
    `);

    // siniestros.dictamen_supervisor ─ antes llamado dictamen_ajustador
    await q(`
      ALTER TABLE siniestros
        MODIFY COLUMN dictamen_supervisor VARCHAR(255) NULL
          COMMENT 'Dictamen final emitido por el supervisor tras validar el levantamiento del ajustador'
    `);

    // siniestros.perdida_total ─ columna nueva; cuando es 1 el presupuesto queda NULL
    await q(`
      ALTER TABLE siniestros
        MODIFY COLUMN perdida_total TINYINT(1) NOT NULL DEFAULT 0
          COMMENT 'Indica si el siniestro es pérdida total: 1 = sí (presupuesto_reparacion queda NULL), 0 = no'
    `);
  },

  async down(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    await q(`
      ALTER TABLE polizas
        MODIFY COLUMN estatus_poliza TINYINT(1) NOT NULL DEFAULT 1
          COMMENT ''
    `);

    await q(`
      ALTER TABLE siniestros
        MODIFY COLUMN numero_reporte VARCHAR(30) NOT NULL
          COMMENT ''
    `);

    await q(`
      ALTER TABLE siniestros
        MODIFY COLUMN presupuesto_reparacion DECIMAL(12,2) NULL
          COMMENT ''
    `);

    await q(`
      ALTER TABLE siniestros
        MODIFY COLUMN dictamen_supervisor VARCHAR(255) NULL
          COMMENT ''
    `);

    await q(`
      ALTER TABLE siniestros
        MODIFY COLUMN perdida_total TINYINT(1) NOT NULL DEFAULT 0
          COMMENT ''
    `);
  }
};
