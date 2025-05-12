import { carregar_dados_painel } from "./utils.js";


window.onload = () => {
    const url = 'http://localhost/voting-system/server-side/api/painel.php';

    carregar_dados_painel(url);
}