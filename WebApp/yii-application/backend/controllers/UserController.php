<?php

namespace backend\controllers;

use common\models\User;
use Yii;
use yii\web\NotFoundHttpException;

class UserController extends \yii\web\Controller
{
    public function actionIndex()
    {
        // Fetch all users from the database
        $users = User::find()->all();

        // Pass the users array to the view
        return $this->render('//site/index', [
            'users' => $users, // Key point: This makes `$users` available in the view
        ]);
    }
    public function actionAssignRole($id)
    {
        $user = User::findOne($id);
        if (!$user) {
            throw new NotFoundHttpException('User not found.');
        }

        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        $currentRole = $auth->getRolesByUser($user->id);
        $currentRole = reset($currentRole)->name ?? null; // Get current role name

        if (Yii::$app->request->post()) {
            $roleName = Yii::$app->request->post('User')['role'];
            $role = $auth->getRole($roleName);
            $auth->revokeAll($user->id); // Remove previous roles
            $auth->assign($role, $user->id); // Assign the new role

            Yii::$app->session->setFlash('success', 'Role assigned successfully.');
            return $this->redirect(['index']);
        }

        return $this->render('assign-role', [
            'user' => $user,
            'roles' => $roles,
            'currentRole' => $currentRole,
        ]);
    }
}
