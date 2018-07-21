function db()
{
    $(".ui.form").form(
    {
        email: {
            identifier: 'email',
            rules: [
                {
                    type: 'email',
                    prompt: '请输入有效的邮箱'
                }]
       },
        user: {
            identifier: 'user',
            rules: [
                {
                    type: 'minLength[5]',
                    prompt: '用户名至少为五位'
                }]
        },  
		password_1: {
            identifier: 'password_1',
            rules: [
                {
                    type: 'minLength[6]',
                    prompt: '密码至少为六位'
                }]
        },
		password_2: {
            identifier: 'password_2',
            rules: [
                {
                    type: 'match[password_1]',
                    prompt: '两次密码不相同'
                }]
        }
    });
}



