'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    await queryInterface.sequelize.query(`
      CREATE PROCEDURE sp_get_polizas_by_usuario(IN p_usuario_id INT)
      BEGIN
        SELECT
          p.id,
          p.numero_poliza,
          p.placas,
          p.fecha_inicio,
          p.fecha_fin,
          p.estatus_poliza,
          v.marca,
          v.modelo,
          v.anio,
          v.version,
          v.tipo_vehiculo,
          s.nombre_seguro,
          s.nivel,
          s.deducible_porcentaje,
          c.nombre_comercial AS compania
        FROM polizas p
        JOIN vehiculos        v ON v.id = p.catalogo_vehiculo_id
        JOIN seguros          s ON s.id = p.seguro_id
        JOIN companias_seguros c ON c.id = s.compania_id
        WHERE p.usuario_id = p_usuario_id
        ORDER BY p.fecha_inicio DESC;
      END
    `);
  },

  async down(queryInterface) {
    await queryInterface.sequelize.query('DROP PROCEDURE IF EXISTS sp_get_polizas_by_usuario');
  }
};
