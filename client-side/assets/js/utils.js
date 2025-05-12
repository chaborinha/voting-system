// arquivo para ciar funções

// função para forçar o usuário a usar reCAPTCHA
export function verifica_recaptcha() {
    if (grecaptcha.getResponse() != "") return true;

    alert('use o recaptcha');
    return false;
}

// função para enviar dados para o servidor
export function verifica_dados(url, nome, senha, recaptchaToken) {
    // Validação simples para evitar campos vazios
    if (!nome || !senha || !recaptchaToken) {
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
        body: JSON.stringify({
            nome: nome,
            senha: senha,
            "g-recaptcha-response": recaptchaToken
        })
    })
    .then(response => response.json())
    .then(dados => {
        console.log(dados);
        if (dados.status == 'success')
        {
            window.location.href = 'http://localhost/voting-system/client-side/painel.html';
        }
        const p = document.createElement('p');
        p.textContent = dados.mensagem;
        document.body.appendChild(p);
    })
    .catch(error => {
        console.error("Erro:", error);
        const p = document.createElement('p');
        p.textContent = 'Erro ao conectar ao servidor.';
        document.body.appendChild(p);
    });
}


export function verifica_nome() {

}

export function verifica_email() {

}

export function verifica_senha() {

}