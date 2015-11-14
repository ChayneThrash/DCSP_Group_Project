function NewPasswordValidator(pwdId, pwdConfId) {
    this.pwdId = pwdId;
    this.pwdConfId = pwdConfId;
    this.pwdValid = false;
    this.pwdConfValid = false;
}

NewPasswordValidator.prototype = {
    constructor: NewPasswordValidator,

    validateNewPwd: function () {
        var pwd = $(this.pwdId).val();
        this.pwdValid = (pwd.length != 0);
        this.validateNewPwdConf();
    },

    validateNewPwdConf: function () {
        var pwdConf = $(this.pwdConfId).val();
        var pwd = $(this.pwdId).val();
        this.pwdConfValid = (pwdConf === pwd);
    }
}