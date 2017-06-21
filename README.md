# OpenLDAP
OpenLDAP

#一、环境说明：
系统版本：CentOS Linux release 7.2.1511 (Core) 

内核版本：3.10.0-327.el7.x86_64

软件版本：
openldap-servers-2.4.40-13.el7.x86_64

openldap-2.4.40-13.el7.x86_64

openldap-devel-2.4.40-13.el7.x86_64

compat-openldap-2.3.43-5.el7.x86_64

openldap-clients-2.4.40-13.el7.x86_64

#二、安装OpenLDAP
## 1.安装
`# yum install openldap openldap-servers openldap-clients openldap-devel compat-openldap` 

`# yum install -y nscd-pam-ldapd nss-* pcre pcre-* --skip-broken  `

如果需要运行用户本地查询LDAP服务，则需要安装以下依赖包

nss-pam-ldapd

`# yum install -y nscd-pam-ldapd nss-* pcre pcre-* --skip-broken `
