<?php

require_once __DIR__ . '/testframework.php';

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../modules/database.php';
require_once __DIR__ . '/../modules/page.php';

$testFramework = new TestFramework();
// test 1: check database connection
function testDbConnection()
{
    global $config;
    $database = new Database($config['db']['path']);
    if ($database) {
        return "Database connection test passed.";
    } else {
        return "Database connection test failed.";
    }
}

// test 2: test count method
function testDbCount()
{
    global $config;
    $database = new Database($config['db']['path']);

    // Проверяем, существует ли таблица 'page'
    $result = $database->Fetch("SELECT name FROM sqlite_master WHERE type='table' AND name='page'");
    if (empty($result)) {
        return "Count test failed: table 'page' does not exist.";
    }

    $count = $database->Count('page');
    if ($count >= 0) {
        return "Count test passed. Count: $count";
    } else {
        return "Count test failed.";
    }
}


// test 3: test create method
function testDbCreate()
{
    global $config;
    $database = new Database($config['db']['path']);

    $result = $database->Fetch("SELECT name FROM sqlite_master WHERE type='table' AND name='page'");
    if (empty($result)) {
        return "Count test failed: table 'page' does not exist.";
    }

    $data = array(
        'name' => 'Test name',
        'content' => 'Test Content'
    );
    $id = $database->Create('page', $data);
    if ($id > 0) {
        return "Create test passed. New row ID: $id";
    } else {
        return "Create test failed.";
    }
}

// test 4: test read method
function testDbRead()
{
    global $config;
    $database = new Database($config['db']['path']);

    $result = $database->Fetch("SELECT name FROM sqlite_master WHERE type='table' AND name='page'");
    if (empty($result)) {
        return "Count test failed: table 'page' does not exist.";
    }

    $id = 1; // change this to the ID of the row you want to read
    $data = $database->Read('page', $id);
    if ($data) {
        return "Read test passed. Data: " . print_r($data, true);
    } else {
        return "Read test failed.";
    }
}

// add tests
$testFramework->add('Database connection', 'testDbConnection');
$testFramework->add('Table count', 'testDbCount');
$testFramework->add('Data create', 'testDbCreate');
$testFramework->add('Data read', 'testDbRead');

// run tests
$testFramework->run();

echo $testFramework->getResult();
