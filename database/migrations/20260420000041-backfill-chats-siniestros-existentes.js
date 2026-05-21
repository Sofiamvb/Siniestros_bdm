'use strict';

/**
 * Rellena la tabla chats para todos los siniestros que no tienen entrada,
 * es decir, los registrados antes del trigger tr_crear_chat_siniestro (mig 32).
 */
module.exports = {
  async up(queryInterface) {
    await queryInterface.sequelize.query(`
      INSERT IGNORE INTO chats (siniestro_id)
      SELECT id FROM siniestros
      WHERE id NOT IN (SELECT siniestro_id FROM chats)
    `);
  },

  async down(queryInterface) {
    // No se puede revertir un backfill de datos de forma segura
  }
};
