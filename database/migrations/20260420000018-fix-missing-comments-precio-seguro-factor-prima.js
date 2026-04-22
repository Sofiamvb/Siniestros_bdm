'use strict';

/**
 * Agrega COMMENT a columnas que fueron añadidas sin descripción:
 *  - vehiculos.precio_seguro  (migración 20260419000001)
 *  - seguros.factor_prima     (migración 20260419000011)
 *  - usuarios.foto            (cambiada a LONGBLOB sin COMMENT)
 */
/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    await q(`
      ALTER TABLE vehiculos
        MODIFY COLUMN precio_seguro DECIMAL(10,2) NOT NULL DEFAULT 0.00
          COMMENT 'Precio base del vehículo usado para calcular la prima anual del seguro (prima = precio_seguro × factor_prima)'
    `);

    await q(`
      ALTER TABLE seguros
        MODIFY COLUMN factor_prima DECIMAL(5,4) NOT NULL DEFAULT 0.0500
          COMMENT 'Factor multiplicador para el cálculo de la prima anual: prima = vehiculo.precio_seguro × factor_prima'
    `);

    await q(`
      ALTER TABLE usuarios
        MODIFY COLUMN foto LONGBLOB NULL
          COMMENT 'Foto de perfil del usuario almacenada como binario LONGBLOB; se sirve como base64 en la sesión'
    `);
  },

  async down(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    await q(`
      ALTER TABLE vehiculos
        MODIFY COLUMN precio_seguro DECIMAL(10,2) NOT NULL DEFAULT 0.00
          COMMENT ''
    `);

    await q(`
      ALTER TABLE seguros
        MODIFY COLUMN factor_prima DECIMAL(5,4) NOT NULL DEFAULT 0.0500
          COMMENT ''
    `);

    await q(`
      ALTER TABLE usuarios
        MODIFY COLUMN foto LONGBLOB NULL
          COMMENT ''
    `);
  }
};
