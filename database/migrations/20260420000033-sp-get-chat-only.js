'use strict';

/**
 * El trigger tr_crear_chat_siniestro ya crea el chat al registrar cada siniestro.
 * Se elimina sp_get_or_create_chat y se reemplaza por sp_get_chat_by_siniestro
 * que solo hace el SELECT correspondiente.
 */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    await q(`DROP PROCEDURE IF EXISTS sp_get_or_create_chat`);
    await q(`DROP PROCEDURE IF EXISTS sp_get_chat_by_siniestro`);
    await q(`
      CREATE PROCEDURE sp_get_chat_by_siniestro(IN p_siniestro_id INT)
      BEGIN
        SELECT id AS chat_id FROM chats WHERE siniestro_id = p_siniestro_id;
      END
    `);
  },

  async down(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    await q(`DROP PROCEDURE IF EXISTS sp_get_chat_by_siniestro`);
    await q(`
      CREATE PROCEDURE sp_get_or_create_chat(IN p_siniestro_id INT)
      BEGIN
        INSERT IGNORE INTO chats (siniestro_id) VALUES (p_siniestro_id);
        SELECT id AS chat_id FROM chats WHERE siniestro_id = p_siniestro_id;
      END
    `);
  }
};
