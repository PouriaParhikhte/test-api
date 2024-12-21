const loginButton = document.getElementById('loginButton'),
    signupForm = document.forms[0],
    loginForm = document.forms[1],
    signupErrorMessage = document.getElementById('signupErrorMessage'),
    loginErrorMessage = document.getElementById('loginErrorMessage');

if (signupForm !== undefined)
    signupForm.addEventListener('submit', async e => {
        e.preventDefault();
        const response = await fetch(signupForm.getAttribute('action'), {
            method: signupForm.getAttribute('method'),
            headers: {
                'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8',
                'type': 'xhr'
            },
            body: JSON.stringify({
                username: signupForm[0].value,
                password: signupForm[1].value,
            })
        });
        signupErrorMessage.textContent = await response.text();
    });

if (loginForm !== undefined)
    loginForm.addEventListener('submit', async e => {
        const token = decodeURIComponent(document.cookie).split('token=');
        e.preventDefault();
        const response = await fetch(loginForm.getAttribute('action'), {
            method: loginForm.getAttribute('method'),
            headers: {
                'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8',
                'type': 'xhr'
            },
            body: JSON.stringify({
                username: loginForm[0].value,
                password: loginForm[1].value
            })
        });
        const message = await response.text(),
            status = await response.status,
            headers = response.headers.get('Authorization');
        if (!headers || headers.split('Bearer=')[1] !== token[1])
            loginErrorMessage.textContent = 'توکن نامعتبر!';
        else if (status === 200)
            window.location.href = 'api/user/dashboard';
        else
            loginErrorMessage.textContent = message ?? authorizationError;
    });