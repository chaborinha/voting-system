// importando funções
import { verifica_recaptcha } from "./utils.js";
import { verifica_dados } from "./utils.js";


// mandando dados do usuário para verificar credenciais de login (passo 1)

// pegando formulário de login e passando um evento para ele
document.querySelector('.form_login').addEventListener('submit', (event) => {

    // evitando submisão do formulário e recarregamento da página
    event.preventDefault();

    // pegando valores do input nome e senha
    let nome = document.querySelector('.nome').value;
    let senha = document.querySelector('.senha').value;
    let recaptchaToken = grecaptcha.getResponse();;

    // verificando se os inputs foram preenchidos
    if (nome == '' || senha == '') {
        alert('todos os campos devem ser preenchidos')
        return
    }

    // verificando recaptcha
    verifica_recaptcha();

    // nome url
    const url = 'http://localhost/voting-system/server-side/api/login.php';
    verifica_dados(url, nome, senha, recaptchaToken);
});