<?php
include "db.php";

/* ================== LOCK ================== */
$lock = fopen("/tmp/print_worker.lock", "c");
if (!flock($lock, LOCK_EX | LOCK_NB)) {
    exit;
}

/* ================== LOGGER ================== */
function log_worker($msg) {
    file_put_contents(
        __DIR__ . "/logs/worker.log",
        "[" . date("Y-m-d H:i:s") . "] $msg\n",
        FILE_APPEND
    );
}

log_worker("Worker started");

/* ================== LOOP ================== */
while (true) {

    $sql = "
        SELECT id
        FROM print_jobs
        WHERE payment_status='paid'
        AND print_status='waiting'
        LIMIT 1
    ";

    $result = $conn->query($sql);

    if ($result->num_rows === 0) {
        sleep(2);
        continue;
    }

    $job = $result->fetch_assoc();
    $id = (int)$job['id'];

    // ATOMIC UPDATE
    $conn->query("
        UPDATE print_jobs
        SET print_status='printing'
        WHERE id=$id AND print_status='waiting'
    ");

    if ($conn->affected_rows === 0) {
        continue;
    }

    log_worker("Printing job $id");

    try {
        // SIMULASI PRINT
        sleep(3);

        $conn->query("
            UPDATE print_jobs
            SET print_status='done'
            WHERE id=$id
        ");

        log_worker("Job $id done");

    } catch (Throwable $e) {

        log_worker("ERROR job $id: " . $e->getMessage());

        $conn->query("
            UPDATE print_jobs
            SET print_status='error'
            WHERE id=$id
    )};
    }
}

