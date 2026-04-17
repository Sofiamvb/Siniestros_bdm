<?php

namespace Model;

class Team extends ActiveRecord
{
    public static function all(): array
    {
        $db = static::getDB();
        $sql = 'SELECT id_team, country FROM Team ORDER BY country ASC';
        $result = $db->query($sql);

        $teams = [];
        while ($row = $result->fetch_assoc()) {
            $teams[] = $row;
        }

        return $teams;
    }

    private static function getNextId(): int
    {
        $db = static::getDB();
        $sql = 'SELECT COALESCE(MAX(id_team), 0) + 1 AS next_id FROM Team';
        $result = $db->query($sql);
        $row = $result->fetch_assoc();

        return (int)($row['next_id'] ?? 1);
    }

    public static function create(array $data): bool
    {
        $db = static::getDB();
        $nextId = static::getNextId();
        $flag = null;

        $sql = 'INSERT INTO Team (id_team, country, players_amount, `group`, team_fact, flag, registered_by) VALUES (?, ?, ?, ?, ?, ?, ?)';
        $stmt = $db->prepare($sql);
        $stmt->bind_param(
            'isissbi',
            $nextId,
            $data['country'],
            $data['players_amount'],
            $data['group'],
            $data['team_fact'],
            $flag,
            $data['registered_by']
        );
        $stmt->send_long_data(5, $data['flag']);

        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }
}
