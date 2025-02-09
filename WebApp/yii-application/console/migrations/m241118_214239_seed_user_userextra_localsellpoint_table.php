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
        // Step 1: Insert users with new real names
        $this->batchInsert('user', ['username', 'email', 'password_hash', 'auth_key', 'created_at', 'updated_at', 'verification_token'], [
            ['admin', 'admin@example.com', Yii::$app->security->generatePasswordHash('admin'), Yii::$app->security->generateRandomString(), 0,0,'0'],
            ['emily', 'emily@example.com', Yii::$app->security->generatePasswordHash('supplier1'), Yii::$app->security->generateRandomString(),0,0,'0'],
            ['julia', 'julia@example.com', Yii::$app->security->generatePasswordHash('supplier2'), Yii::$app->security->generateRandomString(), 0,0,'0'],
            ['matthew', 'matthew@example.com', Yii::$app->security->generatePasswordHash('manager1'), Yii::$app->security->generateRandomString(),0,0,'0'],
            ['david', 'david@example.com', Yii::$app->security->generatePasswordHash('manager2'), Yii::$app->security->generateRandomString(), 0,0,'0'],
            ['lucas', 'lucas@example.com', Yii::$app->security->generatePasswordHash('salesperson1'), Yii::$app->security->generateRandomString(),0,0,'0'],
            ['john', 'john@example.com', Yii::$app->security->generatePasswordHash('salesperson2'), Yii::$app->security->generateRandomString(), 0,0,'0'],
            ['clara', 'clara@example.com', Yii::$app->security->generatePasswordHash('guide1'), Yii::$app->security->generateRandomString(), 0,0,'0'],
            ['sophie', 'sophie@example.com', Yii::$app->security->generatePasswordHash('guide2'), Yii::$app->security->generateRandomString(), 0,0,'0'],
            ['olivia', 'olivia@example.com', Yii::$app->security->generatePasswordHash('client1'), Yii::$app->security->generateRandomString(),0,0,'0'],
            ['luke', 'luke@example.com', Yii::$app->security->generatePasswordHash('client2'), Yii::$app->security->generateRandomString(),0,0,'0'],
        ]);

        // Step 2: Retrieve the user IDs (after insert)
        $adminIdSupplier = (new \yii\db\Query())->select('id')->from('user')->where(['username' => 'admin'])->scalar();
        $userIdSupplier1 = (new \yii\db\Query())->select('id')->from('user')->where(['username' => 'emily'])->scalar();
        $userIdSupplier2 = (new \yii\db\Query())->select('id')->from('user')->where(['username' => 'julia'])->scalar();

        // Step 3: Insert localsellpoint records with the correct user_id
        $this->batchInsert('localsellpoint', ['user_id', 'address', 'name' , 'status'], [
            [$adminIdSupplier, 'website', 'Website', 1],
            [$userIdSupplier1, 'Emily\'s Address, 123 Main Street', 'Emily\'s Sellpoint', 1],
            [$userIdSupplier2, 'Julia\'s Address, 456 Oak Street', 'Julia\'s Sellpoint', 1],
        ]);
        $adminSellPoint = (new \yii\db\Query())->select('id')->from('localsellpoint')->where(['user_id' => $adminIdSupplier])->scalar();
        $localsellpointId1 = (new \yii\db\Query())->select('id')->from('localsellpoint')->where(['user_id' => $userIdSupplier1])->scalar();
        $localsellpointId2 = (new \yii\db\Query())->select('id')->from('localsellpoint')->where(['user_id' => $userIdSupplier2])->scalar();

        // Step 4: Insert userextra records with updated data
        $usersData = [
            'admin' => ['nif' => 987654222, 'address' => 'Admin Address', 'phone' => '0987654321','photo'=> 'E35moiq2WSjIUs6T.jpeg', 'supplier' => $adminIdSupplier, 'localsellpoint_id' => $adminSellPoint],
            'emily' => ['nif' => 987654321, 'address' => 'Emily\'s Address, 123 Main Street', 'phone' => '0987654321','photo'=> 'P8Sx4JRg_2S5_zni.jpeg', 'supplier' => $userIdSupplier1, 'localsellpoint_id' => $localsellpointId1],
            'julia' => ['nif' => 567890123, 'address' => 'Julia\'s Address, 456 Oak Street', 'phone' => '5678901234', 'photo'=> 'kXVn4GehTmqUMez3.jpg','supplier' => $userIdSupplier2, 'localsellpoint_id' => $localsellpointId2],
            'matthew' => ['nif' => 345678901, 'address' => 'Matthew\'s Address, 789 Pine Street', 'phone' => '3456789012','photo'=> 'rLB2CUDgIzhuja5t.jpg', 'supplier' => $userIdSupplier1, 'localsellpoint_id' => $localsellpointId1],
            'david' => ['nif' => 234567890, 'address' => 'David\'s Address, 321 Birch Street', 'phone' => '2345678901','photo'=> 'X4vuAZjVLs9TZlkt.jpeg', 'supplier' => $userIdSupplier2, 'localsellpoint_id' => $localsellpointId2],
            'lucas' => ['nif' => 876543210, 'address' => 'Lucas\'s Address, 987 Cedar Street', 'phone' => '8765432109','photo'=> '5sWR4L_-iVSMrG4T.jpg', 'supplier' => $userIdSupplier1, 'localsellpoint_id' => $localsellpointId1],
            'john' => ['nif' => 765432109, 'address' => 'John\'s Address, 654 Maple Street', 'phone' => '7654321098', 'photo'=> 'zJ4MUbJrB9KfwYN7.jpeg','supplier' => $userIdSupplier2, 'localsellpoint_id' => $localsellpointId2],
            'clara' => ['nif' => 654321098, 'address' => 'Clara\'s Address, 741 Elm Street', 'phone' => '6543210987','photo'=> 'dNB5yZv8IgGoVm4D.jpg', 'supplier' => $userIdSupplier1, 'localsellpoint_id' => $localsellpointId1],
            'sophie' => ['nif' => 543210987, 'address' => 'Sophie\'s Address, 852 Redwood Avenue', 'phone' => '5432109876','photo'=> 'sOaAX7bSet74Myea.jpg', 'supplier' => $userIdSupplier2, 'localsellpoint_id' => $localsellpointId2],
            'olivia' => ['nif' => 432109876, 'address' => 'Olivia\'s Address, 963 Palm Lane', 'phone' => '4321098765','photo'=> 'OyvGbaj69i9ZjLAk.jpeg', 'supplier' => 0, 'localsellpoint_id' => null],
            'luke' => ['nif' => 321098765, 'address' => 'Luke\'s Address, 159 Oak Lane', 'phone' => '3210987654','photo'=> 'elQNDZvUmyavSxMS.jpg', 'supplier' => 0, 'localsellpoint_id' => null],
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
                    'photo' => $data['photo'],
                    'supplier' => $data['supplier'],
                ]);
            }
        }

        // Step 5: Create roles and assign to users (if necessary)
        $auth = Yii::$app->authManager;

        // Define roles based on usernames
        $roles = [
            'admin' => ['admin'],
            'supplier' => ['emily', 'julia'],
            'manager' => ['matthew', 'david'],
            'salesperson' => ['lucas', 'john'],
            'guide' => ['clara', 'sophie'],
            'client' => ['olivia', 'luke'],
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
