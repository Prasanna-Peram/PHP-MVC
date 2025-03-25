<?php
namespace App\Models;

use Bdt\Example\BaseModel;

class Post extends BaseModel
{
    public function save(): void
    {
        $currentTimestamp = date('Y-m-d H:i:s');
        if (!isset($this->data['created_at'])) {
            $this->data['created_at'] = $currentTimestamp; 
        }
        $this->data['updated_at'] = $currentTimestamp; 
        parent::save(); 
    }
    public static function count(): int
    {
        $stmt = self::$connection->executeQuery('SELECT COUNT(*) FROM posts');
        $result = $stmt->fetchAssociative();
        return $result ? (int) $result['COUNT(*)'] : 0;
    } 
}