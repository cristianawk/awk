#!/usr/bin/env python
# -*- coding: latin1 -*-
# Author: Roger Januario Estefani
# Date: 27/05/2011
from email.MIMEMultipart import MIMEMultipart
from email.MIMEText import MIMEText
from time import localtime, strftime
import smtplib
import re
import pgdb
import ConfigParser
import os

diretorio_nome = os.path.dirname(__file__)
#print diretorio_nome
# Le o arquivo de configuração
config = ConfigParser.ConfigParser()
config.read("%s/emp.conf"%(diretorio_nome))
#print config.get('servidor', 'host')
AUTHREQUIRED = 0

#função que envia o email
def sendMailEmp(subject, cc, nomeSender, recipients, mesage):

    server = smtplib.SMTP(config.get('email', 'smtpserver'), 25)
    #print server
    #exit(0)
    msg = MIMEMultipart('related')
    msg['Subject'] = subject
    msg['Date'] = strftime("%a, %d %b %Y %H:%M:%S -0300", localtime())
    msg['Cc'] = cc
    msg['Bcc'] = config.get('email', 'bcc_sender')
    msg['From'] = nomeSender
    msg['To'] = recipients
    msg.preamble = 'This is a multi-part message in MIME format.'

    msgAlternative = MIMEMultipart('alternative')
    msg.attach(msgAlternative)

    t = re.sub("< */? *\w+ */?\ *>", "", mesage)

    msgText = MIMEText(t)
    msgAlternative.attach(msgText)

    msgText = MIMEText(mesage,'html')
    msgAlternative.attach(msgText)

    if AUTHREQUIRED:
        server.login(config.get('email', 'smtpuser'), config.get('email', 'smtppass'))

    try:
        smtpresult = server.sendmail(config.get('email', 'sender'), recipients, msg.as_string())
        #smtpresultbcc = server.sendmail(config.get('email', 'sender'), config.get('email', 'bcc_sender'), msg.as_string())
    except:
        print "ERRO AO ENVIAR EMAIL PARA %s"%(recipients)
        #continue

    server.quit()


# Conexao com o banco de dados
con = pgdb.connect(host=config.get('servidor', 'host'), database=config.get('servidor', 'database'), user=config.get('servidor', 'user'), password=config.get('servidor', 'password'))
cur = con.cursor()

# Realiza uma consulta no banco
cur.execute('''SELECT a.id_mtr_usuario, a.nm_usuario, to_char(b.dt_empenho, 'DD/MM/YYYY') AS dt_empenho,
                to_char(b.dt_pagamento, 'DD/MM/YYYY') AS dt_pagamento,
                UPPER(b.ds_empenho) AS ds_empenho,
                c.nm_unidade, b.ds_repasse, d.nm_posto, b.ds_email_comandante, a.ds_email_usuario
                FROM empenhos AS b JOIN usuarios AS a ON (a.id_mtr_usuario = b.id_mtr_gestor)
                JOIN unidade_operacional AS c USING (id_unidade)
                JOIN posto_graduacao AS d USING (id_posto)
                JOIN tp_situacao_empenho AS e USING (cd_situacao_empenho)
                WHERE b.ch_visualizar = 'S' AND ch_enviar_email = 'S' AND (dt_pagamento + 71 ) < CURRENT_DATE AND
                dt_aprovacao IS NULL
                ORDER BY c.id_unidade, a.nm_usuario, b.dt_empenho''')
# Recupera os resultados
empenhos = cur.fetchall()
#print empenhos

# Realiza uma consulta no banco
cur.execute('''SELECT a.id_mtr_usuario, a.nm_usuario, to_char(b.dt_empenho, 'DD/MM/YYYY') AS dt_empenho,
                to_char(b.dt_pagamento, 'DD/MM/YYYY') AS dt_pagamento,
                UPPER(b.ds_empenho) AS ds_empenho,
                c.nm_unidade, b.ds_repasse, d.nm_posto, b.ds_email_comandante, a.ds_email_usuario
                FROM empenhos AS b JOIN usuarios AS a ON (a.id_mtr_usuario = b.id_mtr_gestor)
                JOIN unidade_operacional AS c USING (id_unidade)
                JOIN posto_graduacao AS d USING (id_posto)
                JOIN tp_situacao_empenho AS e USING (cd_situacao_empenho)
                WHERE b.ch_visualizar = 'S'
                AND ch_enviar_email = 'S'
                AND ch_tc28 = 'S'
                AND (dt_pagamento + 71 ) < CURRENT_DATE
                AND dt_entrega_tc28 IS NULL
                AND dt_aprovacao IS NULL
                ORDER BY c.id_unidade, a.nm_usuario, b.dt_empenho''')
# Recupera os resultados
entregastc28 = cur.fetchall()
#print empenhos
con.close()

nomeSender = 'Dlf Sistema Controle de Prazos <%s>'%(config.get('email', 'sender'))

#
# Empenhos Atrazados
#

if empenhos != []:

    for empenho in empenhos:
        #print empenho[0]
        subject = 'Diretoria de Logística e Finanças do CBMSC - Devolução do Empenho Nº %s'%(empenho[4])
        recipients = '%s'%(empenho[8])
        cc = '%s'%(empenho[9])
        mesage = '''
                    <html>
					<head>
						<title>Controle de Prazos</title>
					</head>
					<body>
						<p>Senhor Cmt, Diretor ou Chefe, CC ao Gestor de adiantamento,</p>
						<p><b>A Diretoria de Logística e Finanças do CBMSC alerta:</b>
						detectamos que o gestor %s Mtcl %s - %s encontra-se em atraso
						com a Prestação de Contas dos recursos repassados através
						do Empenho Nº %s, estando assim sujeito a penalidade de multa e sanções administrativas.
						</p>
					<p>Solicitamos providências urgentes, de forma a regularizar a situação o mais breve possível.</p>
					<p>DIRETORIA DE LOGÍSTICA E FINANÇAS/CBMSC<br>
						Fone: (48)3271-1196 / (48)3271-1181</p>
					</body>
					</html>
                '''%(empenho[7], empenho[0], empenho[1], empenho[4])

        #envia email
        sendMailEmp(subject, cc, nomeSender, recipients, mesage)



#
# Entrega da TC-28 Atrazados
#


if entregastc28 != []:

    for entrega in entregastc28:
        #print empenho[0]
        subject = 'Diretoria de Logística e Finanças do CBMSC - Entrega da TC-28 do Empenho Nº %s'%(entrega[4])
        recipients = '%s'%(entrega[8])
        cc = '%s'%(entrega[9])
        mesage = '''
                    <html>
					<head>
						<title>Controle de Prazos</title>
					</head>
					<body>
						<p>Senhor Cmt, Diretor ou Chefe, CC ao Gestor de adiantamento,</p>
						<p><b>A Diretoria de Logística e Finanças do CBMSC alerta:</b>
						detectamos que o gestor %s Mtcl %s - %s encontra-se em atraso
						com o envio do TC-28
						do Empenho Nº %s, para a devida publicação no Diário Oficial do Estado.
						</p>
					<p>Para a regularização, basta enviar Nota Eletrônica (email) com o arquivo do TC-28 com extensão editável, para os seguintes endereços: dlfcciaux3@cbm.sc.gov.br  e  dlfccich@cbm.sc.gov.br .</p>
					<p>DIRETORIA DE LOGÍSTICA E FINANÇAS/CBMSC<br>
						Fone: (48)3271-1196 / (48)3271-1181</p>
					</body>
					</html>
                '''%(entrega[7], entrega[0], entrega[1], entrega[4])

        #envia email
        sendMailEmp(subject, cc, nomeSender, recipients, mesage)
