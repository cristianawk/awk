#!/usr/bin/env python
# -*- coding: latin1 -*-
# Author: Roger Januario Estefani
# Date: 31/05/2011
import os
import pgdb
import ConfigParser

diretorio_nome = os.path.dirname(__file__)
#print diretorio_nome
# Le o arquivo de configuração
config = ConfigParser.ConfigParser()
config.read("%s/emp.conf"%(diretorio_nome))

# Conexao com o banco de dados 
con = pgdb.connect(host=config.get('servidor', 'host'), database=config.get('servidor', 'database'), user=config.get('servidor', 'user'), password=config.get('servidor', 'password'))
cur = con.cursor()

# Realiza uma consulta no banco
cur.execute('''
            UPDATE empenhos SET cd_situacao_empenho = 'EU' FROM empenhos_recebimentos AS b 
            WHERE empenhos.id_empenho=b.id_empenho AND ch_visualizar = 'S' AND (dt_pagamento + 61 ) < CURRENT_DATE 
            AND dt_aprovacao IS NULL 
            -- AND (dt_pagamento + 61 ) < dt_recebimento
            ;

            UPDATE empenhos SET cd_situacao_empenho = 'EA' WHERE ch_visualizar = 'S' AND (dt_pagamento + 61 ) < CURRENT_DATE 
            AND dt_aprovacao IS NULL 
            AND (id_empenho NOT IN (SELECT DISTINCT id_empenho FROM empenhos_recebimentos WHERE empenhos.id_empenho=id_empenho 
	        -- AND (empenhos.dt_pagamento + 61 ) < dt_recebimento
	        ) 
	        OR id_empenho IN (SELECT DISTINCT empenhos_devolucoes.id_empenho 
	        FROM empenhos_recebimentos, empenhos_devolucoes WHERE empenhos.id_empenho = empenhos_recebimentos.id_empenho 
	        AND empenhos_devolucoes.id_empenho = empenhos_recebimentos.id_empenho 
	        AND (SELECT MAX(ts_devolucao) FROM empenhos_devolucoes AS a WHERE empenhos_devolucoes.id_empenho = a.id_empenho) > 
	        (SELECT MAX(ts_recebimento) FROM empenhos_recebimentos AS a WHERE empenhos_recebimentos.id_empenho = a.id_empenho)));

            UPDATE empenhos SET cd_situacao_empenho = 'ET' WHERE ch_visualizar = 'S' AND dt_aprovacao IS NULL AND (dt_pagamento + 61 ) > CURRENT_DATE;

            ''')

con.commit()                
con.close()
