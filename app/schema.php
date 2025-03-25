<?php

use \Doctrine\DBAL\Schema\Schema;

// Create new Posts table, as per Doctrine Schema-Representation:
// http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/schema-representation.html
$schema = new Schema();
$posts = $schema->createTable('posts');
$posts->addColumn('id', 'integer', ['autoincrement' => true]);
$posts->addColumn('title', 'string');
$posts->addColumn('body', 'string');
$posts->addColumn('author', 'string', ['notnull' => false]); 
$posts->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP']); 
$posts->addColumn('updated_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP']);
$posts->setPrimaryKey(['id']);

return $schema;