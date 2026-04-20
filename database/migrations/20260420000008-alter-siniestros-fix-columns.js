'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    // numero_reporte: INTEGER → VARCHAR(30)
    await q(`
      ALTER TABLE siniestros
        MODIFY COLUMN numero_reporte VARCHAR(30) NOT NULL
    `);

    // presupuesto_reparacion: STRING → DECIMAL para cálculos correctos
    await q(`
      ALTER TABLE siniestros
        MODIFY COLUMN presupuesto_reparacion DECIMAL(12,2) NULL
    `);
  },

  async down(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    await q(`ALTER TABLE siniestros MODIFY COLUMN numero_reporte INTEGER NOT NULL`);
    await q(`ALTER TABLE siniestros MODIFY COLUMN presupuesto_reparacion VARCHAR(255) NULL`);
  }
};
