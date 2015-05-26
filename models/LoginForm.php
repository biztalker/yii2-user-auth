<?php
namespace thinker_g\UserAuth\models;

use Yii;
use yii\base\Model;
use thinker_g\UserAuth\interfaces\CredentialInterface;
use yii\base\NotSupportedException;

/**
 * Login form
 */
class LoginForm extends Model
{
    /**
     * User model class name.
     * The class must implement interface \thinker_g\UserAuth\interfaces\CredentialInterface .
     * @var string
     */
    public $userModelClass = 'thinker_g\UserAuth\models\User';

    /**
     * Validator method name used by password.
     * Set to "validateSuperPassword" to verify password stored in super agent account model.
     * This must a method exists in the form model (extend this model for adding more validators).
     * @var string
     */
    public $passwordValidator = 'validatePassword';

    public $username;

    public $password;

    /**
     * Whether to keep current user logged in.
     * See option [[autoLoginDuration]] for the duration.
     * @var bool
     */
    public $rememberMe = true;

    /**
     * How long, in seconds, to keep current user logged in.
     * Default to 7 days. This parameter will only be read when [[rememberMe]] is true.
     * @var int
     */
    public $keepLoginDuration;

    private $_user = false;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', $this->passwordValidator],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!($user && $user->validatePassword($this->password))) {
                $errMsg = 'Incorrect username or password.';
                if ($user && !$user->password) {
                    $errMsg = 'Account is not available for login.';
                }
                $this->addError($attribute, $errMsg);
            }

        }
    }

    /**
     * Validates the password stored in super agent account.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateSuperPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!($user && ($superAcct = $user->superAgentAcct) && $superAcct->access_token)) {
                $this->addError($attribute, 'Invalide Credential');
                return;
            }
            if (!Yii::$app->security->validatePassword($this->password, $superAcct->access_token)) {
                $this->addError($attribute, 'Incorrect password');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            //todo keepLoginDuration default value not implemented.
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? $this->keepLoginDuration : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $userModel = Yii::createObject($this->userModelClass);
            if (!$userModel instanceof CredentialInterface) {
                throw new NotSupportedException(
                    get_class($userModel)
                    . ' must implement interface \\thinker_g\\UserAuth\\interfaces\\CredentialInterface.'
                );
            }
            $this->_user = $userModel::findByLogin($this->username);
        }

        return $this->_user;
    }
}
