'use strict';

module.exports = {
  async up(queryInterface, Sequelize) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    // ── Tabla chats (1 chat por siniestro) ───────────────────────────────────
    await queryInterface.createTable('chats', {
      id:           { type: Sequelize.INTEGER, primaryKey: true, autoIncrement: true },
      siniestro_id: { type: Sequelize.INTEGER, allowNull: false, unique: true,
                      references: { model: 'siniestros', key: 'id' } },
      created_at:   { type: Sequelize.DATE, defaultValue: Sequelize.literal('NOW()') },
    });

    // ── Tabla chat_mensajes ───────────────────────────────────────────────────
    await queryInterface.createTable('chat_mensajes', {
      id:         { type: Sequelize.INTEGER, primaryKey: true, autoIncrement: true },
      chat_id:    { type: Sequelize.INTEGER, allowNull: false,
                    references: { model: 'chats', key: 'id' } },
      usuario_id: { type: Sequelize.INTEGER, allowNull: false,
                    references: { model: 'usuarios', key: 'id' } },
      mensaje:    { type: Sequelize.TEXT, allowNull: false },
      created_at: { type: Sequelize.DATE, defaultValue: Sequelize.literal('NOW()') },
    });

    // ── SP: obtener o crear chat por siniestro ────────────────────────────────
    await q(`DROP PROCEDURE IF EXISTS sp_get_or_create_chat`);
    await q(`
      CREATE PROCEDURE sp_get_or_create_chat(IN p_siniestro_id INT)
      BEGIN
        INSERT IGNORE INTO chats (siniestro_id) VALUES (p_siniestro_id);
        SELECT id AS chat_id FROM chats WHERE siniestro_id = p_siniestro_id;
      END
    `);

    // ── SP: enviar mensaje ────────────────────────────────────────────────────
    await q(`DROP PROCEDURE IF EXISTS sp_enviar_mensaje`);
    await q(`
      CREATE PROCEDURE sp_enviar_mensaje(
        IN p_chat_id    INT,
        IN p_usuario_id INT,
        IN p_mensaje    TEXT
      )
      BEGIN
        INSERT INTO chat_mensajes (chat_id, usuario_id, mensaje)
        VALUES (p_chat_id, p_usuario_id, p_mensaje);

        -- Devolver el mensaje recién insertado con datos del usuario
        SELECT
          cm.id,
          cm.mensaje,
          cm.created_at,
          u.nombre,
          u.apellidos,
          u.rol_id
        FROM chat_mensajes cm
        INNER JOIN usuarios u ON u.id = cm.usuario_id
        WHERE cm.id = LAST_INSERT_ID();
      END
    `);

    // ── SP: obtener mensajes (polling incremental) ────────────────────────────
    await q(`DROP PROCEDURE IF EXISTS sp_get_mensajes_chat`);
    await q(`
      CREATE PROCEDURE sp_get_mensajes_chat(
        IN p_chat_id   INT,
        IN p_desde_id  INT
      )
      BEGIN
        SELECT
          cm.id,
          cm.mensaje,
          cm.created_at,
          u.nombre,
          u.apellidos,
          u.rol_id
        FROM chat_mensajes cm
        INNER JOIN usuarios u ON u.id = cm.usuario_id
        WHERE cm.chat_id  = p_chat_id
          AND cm.id       > p_desde_id
        ORDER BY cm.id ASC
        LIMIT 50;
      END
    `);

    // ── SP: verificar que el usuario es participante del siniestro ────────────
    await q(`DROP PROCEDURE IF EXISTS sp_verificar_participante_chat`);
    await q(`
      CREATE PROCEDURE sp_verificar_participante_chat(
        IN p_siniestro_id INT,
        IN p_usuario_id   INT
      )
      BEGIN
        SELECT COUNT(*) AS es_participante
        FROM siniestros s
        INNER JOIN polizas p ON p.id = s.poliza_id
        WHERE s.id = p_siniestro_id
          AND (
            s.ajustador_id  = p_usuario_id
            OR s.supervisor_id = p_usuario_id
            OR p.usuario_id    = p_usuario_id
          );
      END
    `);
  },

  async down(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);
    await q(`DROP PROCEDURE IF EXISTS sp_verificar_participante_chat`);
    await q(`DROP PROCEDURE IF EXISTS sp_get_mensajes_chat`);
    await q(`DROP PROCEDURE IF EXISTS sp_enviar_mensaje`);
    await q(`DROP PROCEDURE IF EXISTS sp_get_or_create_chat`);
    await queryInterface.dropTable('chat_mensajes');
    await queryInterface.dropTable('chats');
  }
};
