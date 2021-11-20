# 1º - Por que aconteceu esse erro? Justifique sua resposta.

Pois o usuário "danilo" não existe no banco de dados dando o erro no de chave estrangeira pois o nome é a chave da tabela

# 2º - Como você resolveria esse problema? Descreva com suas palavras.

Adicionaria o usuário no banco de dados e criaria uma validação para caso o usuario não exista no banco de dados seja retornado uma mensagem pedindo para que seja criado o usuario antes
