<?php

use yii\db\Migration;

/**
 * Class m241118_214239_seed_user_table
 */
class m241118_214239_seed_user_userextra_localsellpoint_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Step 1: Insert users
        $this->batchInsert('user', ['username', 'email', 'password_hash'], [
            ['admin', 'admin@example.com', Yii::$app->security->generatePasswordHash('admin')],
            ['supplier1', 'supplier1@example.com', Yii::$app->security->generatePasswordHash('supplier1')],
            ['supplier2', 'supplier2@example.com', Yii::$app->security->generatePasswordHash('supplier2')],
            ['manager1', 'manager1@example.com', Yii::$app->security->generatePasswordHash('manager1')],
            ['manager2', 'manager2@example.com', Yii::$app->security->generatePasswordHash('manager2')],
            ['salesperson1', 'salesperson1@example.com', Yii::$app->security->generatePasswordHash('salesperson1')],
            ['salesperson2', 'salesperson2@example.com', Yii::$app->security->generatePasswordHash('salesperson2')],
            ['guide1', 'guide1@example.com', Yii::$app->security->generatePasswordHash('guide1')],
            ['guide2', 'guide2@example.com', Yii::$app->security->generatePasswordHash('guide2')],
            ['client1', 'client1@example.com', Yii::$app->security->generatePasswordHash('client1')],
            ['client2', 'client2@example.com', Yii::$app->security->generatePasswordHash('client2')],
        ]);

        // Step 2: Retrieve the user IDs (after insert)
        $userIdSupplier1 = (new \yii\db\Query())->select('id')->from('user')->where(['username' => 'supplier1'])->scalar();
        $userIdSupplier2 = (new \yii\db\Query())->select('id')->from('user')->where(['username' => 'supplier2'])->scalar();

        // Step 3: Insert localsellpoint records with the correct user_id
        $this->batchInsert('localsellpoint', ['user_id', 'address', 'name'], [
            [$userIdSupplier1, 'Supplier1 Address', 'Supplier1 Sellpoint'],
            [$userIdSupplier2, 'Supplier2 Address', 'Supplier2 Sellpoint'],
        ]);

        $localsellpointId1 = (new \yii\db\Query())->select('id')->from('localsellpoint')->where(['user_id' => $userIdSupplier1])->scalar();
        $localsellpointId2 = (new \yii\db\Query())->select('id')->from('localsellpoint')->where(['user_id' => $userIdSupplier2])->scalar();


        // Step 4: Insert userextra records
        $usersData = [
            'supplier1' => ['nif' => 987654321, 'address' => 'Supplier1 Address', 'phone' => '0987654321', 'supplier' => $userIdSupplier1, 'localsellpoint_id' => $localsellpointId1],
            'supplier2' => ['nif' => 567890123, 'address' => 'Supplier2 Address', 'phone' => '5678901234', 'supplier' => $userIdSupplier2, 'localsellpoint_id' => $localsellpointId2],
            'manager1' => ['nif' => 345678901, 'address' => 'Manager1 Address', 'phone' => '3456789012', 'supplier' => $userIdSupplier1, 'localsellpoint_id' => $localsellpointId1],
            'manager2' => ['nif' => 234567890, 'address' => 'Manager2 Address', 'phone' => '2345678901', 'supplier' => $userIdSupplier2, 'localsellpoint_id' => $localsellpointId2],
            'salesperson1' => ['nif' => 876543210, 'address' => 'Salesperson1 Address', 'phone' => '8765432109', 'supplier' => $userIdSupplier1, 'localsellpoint_id' => $localsellpointId1],
            'salesperson2' => ['nif' => 765432109, 'address' => 'Salesperson2 Address', 'phone' => '7654321098', 'supplier' => $userIdSupplier2, 'localsellpoint_id' => $localsellpointId2],
            'guide1' => ['nif' => 654321098, 'address' => 'Guide1 Address', 'phone' => '6543210987', 'supplier' => $userIdSupplier1, 'localsellpoint_id' => $localsellpointId1],
            'guide2' => ['nif' => 543210987, 'address' => 'Guide2 Address', 'phone' => '5432109876', 'supplier' => $userIdSupplier2, 'localsellpoint_id' => $localsellpointId2],
            'client1' => ['nif' => 432109876, 'address' => 'Client1 Address', 'phone' => '4321098765', 'supplier' => null, 'localsellpoint_id' => null],
            'client2' => ['nif' => 321098765, 'address' => 'Client2 Address', 'phone' => '3210987654', 'supplier' => null, 'localsellpoint_id' => null],
        ];

        foreach ($usersData as $username => $data) {
            $user = (new \yii\db\Query())
                ->select('id')
                ->from('user')
                ->where(['username' => $username])
                ->scalar();

            if ($user) {
                $this->insert('userextra', [
                    'user_id' => $user,
                    'localsellpoint_id' => $data['localsellpoint_id'],
                    'nif' => $data['nif'],
                    'address' => $data['address'],
                    'phone' => $data['phone'],
                    'supplier' => $data['supplier'],
                ]);
            }
        }

        // Step 5: Create roles and assign to users (if necessary)
        $auth = Yii::$app->authManager;

        // Define roles based on usernames
        $roles = [
            'admin' => ['admin'],
            'supplier' => ['supplier1', 'supplier2'],
            'manager' => ['manager1', 'manager2'],
            'salesperson' => ['salesperson1', 'salesperson2'],
            'guide' => ['guide1', 'guide2'],
            'client' => ['client1', 'client2'],
        ];

        foreach ($roles as $roleName => $usernames) {
            $role = $auth->getRole($roleName);

            foreach ($usernames as $username) {
                // Fetch user ID
                $userId = (new \yii\db\Query())
                    ->select('id')
                    ->from('user')
                    ->where(['username' => $username])
                    ->scalar();

                if ($userId) {
                    $auth->assign($role, $userId);
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Step 1: Get the users that were created
        $users = ['admin', 'supplier1', 'supplier2', 'manager1', 'manager2', 'salesperson1', 'salesperson2', 'guide1', 'guide2', 'client1', 'client2'];

        // Step 2: Unassign roles from users
        $auth = Yii::$app->authManager;
        foreach ($users as $username) {
            $userId = (new \yii\db\Query())
                ->select('id')
                ->from('user')
                ->where(['username' => $username])
                ->scalar();

            if ($userId) {
                // Remove roles assigned to the user
                $auth->revokeAll($userId);
            }
        }

        // Step 3: Delete userextra records
        foreach ($users as $username) {
            $userId = (new \yii\db\Query())
                ->select('id')
                ->from('user')
                ->where(['username' => $username])
                ->scalar();

            if ($userId) {
                // Delete the corresponding userextra record
                $this->delete('userextra', ['user_id' => $userId]);
            }
        }

        // Step 4: Delete localsellpoint records
        foreach ($users as $username) {
            $userId = (new \yii\db\Query())
                ->select('id')
                ->from('user')
                ->where(['username' => $username])
                ->scalar();

            if ($userId) {
                // Delete the corresponding localsellpoint record
                $this->delete('localsellpoint', ['user_id' => $userId]);
            }
        }

        // Step 5: Delete user records
        foreach ($users as $username) {
            // Delete user record
            $this->delete('user', ['username' => $username]);
        }

        return true;
    }
}