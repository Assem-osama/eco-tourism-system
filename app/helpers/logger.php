<?php

function createLog($databaseConnection, $user_id, $action, $description, $table_name, $record_id) {
    $statement = $databaseConnection->prepare(
        "INSERT INTO audit_logs (user_id, action, description, table_name, record_id)
         VALUES (?, ?, ?, ?, ?)"
    );
    $statement->execute([$user_id, $action, $description, $table_name, $record_id]);
}
