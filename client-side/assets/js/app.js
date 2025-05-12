// importando funções
import { verifica_recaptcha } from "./utils.js";
import { verifica_dados } from "./utils.js";


// pegando formulário de login e passando um evento para ele
document.querySelector('.form_login').addEventListener('submit', (event) => {

    // evitando submisão do formulário e recarregamento da página
    event.preventDefault();

    // declarando as variaveis
    let email = document.querySelector('.email').value;
    let senha = document.querySelector('.senha').value;
    let recaptchaToken = grecaptcha.getResponse();
    const url = 'http://localhost/voting-system/server-side/api/login.php';

    // verificando recaptcha
    verifica_recaptcha();

    verifica_dados(url, email, senha, recaptchaToken);
});