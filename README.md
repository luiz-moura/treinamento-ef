## Para a sua graduação, você terá que desenvolver um sistema WEB de sua escolha, com os seguintes requisitos:
[ ] CRUD de pelo menos um recurso
[ ] Impressão de pelo menos um relatório
[ ] Não pode ter autenticação, isto é, não pode ter login e nem permissões
[ ] Precisa resolver algum problema real, mesmo que em escala reduzida


## Deve ser obrigatoriamente desenvolvido com as seguintes tecnologias:
[ ] Laravel na versão mais recente
[ ] Bootstrap 4
[ ] As páginas devem ser renderizadas no back-end, utilizando a linguagem de layout Blade, do próprio Laravel.
[ ] Javascript só pode ser utilizado para melhoria progressiva (progressive enhancement) da página: quer dizer que ela precisa funcionar (pelo menos num nível básico) mesmo sem JavaScript, mas pode ter funcionalidades que melhoram a experiência do usuário quando o JavaScript estiver ativado, como busca em tempo real, efeitos visuais etc..
[ ] É recomendado o uso da biblioteca JQuery para a manipulaçào da página com JavaScript.

## Adendos
Antes de começar, valide o seu projeto com o responsável pelo treinamento. Uma vez que a aplicação inicial do Laravel esteja configurada e conectada com o banco de dados, aguarde uma aula introdutória de desenvolvimento WEB com o responsável pelo treinamento antes de iniciar o desenvolvimento do projeto. Enquanto isso, vá lendo a documentação do Laravel, que é excelente, ou reforçando os conhecimentos básicos de HTML e CSS.

Orientação importante: ao contrário das listas de exercicios, é recomendado que sejam feitas orientações regulares no decorrer do desenvolvimento do projeto final, e não apenas na revisão final. Possivelmente uma vez ao dia, ou uma vez a cada dois dias. Então faça o melhor que puder por conta própria, mas organize suas dúvidas e prepare-se para apresentar o andamento do projeto regularmente.

O prazo esperado para conclusão desta etapa final do treinamento é entre uma e duas semanas, mas desvios podem ocorrer. Em caso de dúvida, converse com o responsável pelo treinamento.

---------------------
produto

  - estoque a qual ele pertence

estoque
 - area de venda

movimentacao
 - entrada
 - venda (saida)

venda
 -venda que gera movimentacao e dedus na quantidade do estoque
