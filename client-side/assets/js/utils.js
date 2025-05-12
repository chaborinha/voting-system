// arquivo para ciar funções

// função para forçar o usuário a usar reCAPTCHA
export function verifica_recaptcha() {
    if (grecaptcha.getResponse() != "") return true;

    alert('use o recaptcha');
    return false;
}

// função para enviar dados para o servidor
export function verifica_dados(url, email, senha, recaptchaToken) {

    let obj = { email: email, senha: senha, "g-recaptcha-response": recaptchaToken };

    // validação simples para evitar campos vazios
    if (!email || !senha || !recaptchaToken) {
        const p = document.createElement('p');
        p.textContent = 'Preencha usuário, senha e complete o reCAPTCHA.';
        document.body.appendChild(p);
        return;
    }

    fetch(url, {
        method: 'POST',
        headers: {
            "Content-Type": "application/json; charset=utf-8"
        },
        body: JSON.stringify(
            obj
        )
    })
        .then(response => response.json())
        .then(dados => {
            if (dados.status === 'logged') {
                window.location.href = "http://localhost/voting-system/client-side/painel.html";
            }
            const p = document.createElement('p');
            p.textContent = `Olá ${dados.user_name}`;
            document.body.appendChild(p);
        })
        .catch(error => {
            console.error("Erro:", error);
            const p = document.createElement('p');
            p.textContent = 'Ops, algo deu errado.';
            document.body.appendChild(p);
        });
}

export function carregar_dados_painel(url) {
    fetch(url, {
        method: 'GET',
        headers: {
            "Content-Type": "application/json; charset=utf-8"
        }
    })
    .then(response => {
        if (response.status === 200) {
            return response.json();
        } else {
            throw new Error('Ops, algo deu errado');
        }
    })
    .then(dados => {
        if (dados.status === 'fail') {
            window.location.href = "http://localhost/voting-system/client-side/index.html";
            return;
        }

        // mostrar usuário
        const p = document.querySelector('#user');
        if (dados.user && typeof dados.user === 'object') {
            p.textContent = `Olá ${dados.user.nome}`;
        } else {
            p.textContent = `Olá ${dados.user ?? 'erro'}`;
        }

        // mostrar enquetes
        const list_enquetes = document.querySelector('#enquetes');
        dados.enquetes.forEach(enquete => {
            const li = document.createElement('li');
            li.innerHTML = `${enquete.id} | <span class="opacity-50">${enquete.nome}</span>`;
            list_enquetes.appendChild(li);
        });
    })
    .catch(error => {
        console.log(error);
        const p = document.createElement('p');
        p.textContent = 'Ops, algo deu errado.';
        document.body.appendChild(p);
    });
}


export function verifica_nome() {

}

export function verifica_email() {

}

export function verifica_senha() {

}