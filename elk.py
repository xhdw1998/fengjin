# coding=utf8
from elasticsearch import Elasticsearch
import os
import sys
import datetime
import time
import json
from elasticsearch_dsl import Search,Q 
from elasticsearch_dsl.query import MultiMatch,Match
import requests
reload(sys)
sys.setdefaultencoding('utf-8')


listB = []
listC=[]
listD=[]
list_groups=['AUTO_2020-08-15_IP','AUTO_2020-08-16_IP','AUTO_2020-08-17_IP','AUTO_2020-08-18_IP','AUTO_2020-08-19_IP','AUTO_2020-08-20_IP','AUTO_2020-08-21_IP','AUTO_2020-08-22_IP','AUTO_2020-08-23_IP','AUTO_2020-08-24_IP','AUTO_2020-08-25_IP','AUTO_2020-08-26_IP','AUTO_2020-08-27_IP','AUTO_2020-08-28_IP','AUTO_2020-08-29_IP','AUTO_2020-08-30_IP','AUTO_2020-08-31_IP','AUTO_2020-09-01_IP']
headers = {
            "Accept": "application/json",
            "Content-Type": "application/json",
            "Authorization": "Basic YWRtaW46Skp5aF9FRFNAV2g5NjIwMg=="
        }








#提取ES中想要的数据
def esRequest():
    es = Elasticsearch(['http://10.19.1.82:8200'],http_auth=("ip","27825be9d"))
    multi_match = MultiMatch(query='high',fields=['msg.risk_level'])
    s= Search(using=es,index='waf_ct-*').query(multi_match).filter("range",**{'@timestamp':{"from":"now-2m","lt":"now"}})
    s = s[0:9999]
    response = s.execute()
    list1 = []
    for hit in response['hits']['hits']:
        dic = {}
        all = hit['_source']
        try:
            dic['name'] = str(all['name'])
        except Exception as e:
            dic['name'] = '未知设备'
        dic['host'] = str(all['host'])
        dic['src_ip'] = str(all['msg']['src_ip'])
        dic['attack_type'] = str(all['msg']['attack_type'])
        dic['attck_time'] = str(all['msg']['timestamp_human'])
        list1.append(dic)
	#print(list1)
    return list1

#提取攻击的源ip
def choiceSrc_ip(listIp):
    if(listIp == None):
        srcIp = []
    else:
        srcIp = []
        num = 0
        try:
            for ip in listIp:
                srcIp.append(ip['src_ip'])
                num += 1
            srcIp = list(set(srcIp))
        except Exception as e:
            print('ip Error：'+ str(e))

    return srcIp

# 筛选可以封禁的IP
def choiceBanList(src_ip):
    ban = []
    num = 0
    now = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    try:
        for sip in src_ip:
            ipA = src_ip[num].split('.')[0]
            ipB = src_ip[num].split('.')[0]+'.'+src_ip[num].split('.')[1]+'.0.0'
            ipC = src_ip[num].split('.')[0]+'.'+src_ip[num].split('.')[1]+'.'+src_ip[num].split('.')[2]+'.0'
            num += 1
    	    if(sip in listD):
                continue
            elif(ipA == '10'):
                continue
            elif(ipB in listB):
                continue
            elif(ipC in listC):
                continue
            else:
                print('%s,\t%s\t是攻击IP，正在排查是否已经封禁··· '%(now,sip))
                ban.append(sip)
                continue
    except Exception as e:
        str='choiceBanList IP Error'+str(e)
        logs(str)
        print(str)    
    return ban

#封禁IP
def fuckipv4(ban,headers):
    print("feng IP...")
    print(ban)

    
    date_groups = ''
    now = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    dateNow = datetime.datetime.now().strftime('%Y-%m-%d')
    date_groups='AUTO_'+dateNow+'_IP'

    ip2 = ['172.17.226.89','172.17.242.89','172.17.225.88','172.17.241.88','172.22.9.17','172.22.9.18']
    for ip in ban:
        for ip3 in ip2:
	    print("ban start....")
            url = 'http://%s/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafcustomv4wblist'%ip3
            data = {'mafcustomv4wblist':{'GroupStr':date_groups,'IPStart':'%s','IPEnd':'%s','LeftAge':'-1','Action':'2'}}
            data['mafcustomv4wblist']['IPStart'] = data['mafcustomv4wblist']['IPEnd'] = ip
	    data_json = json.dumps(data,encoding='utf-8')
            r = requests.post(url,data = data_json,headers = headers,verify = False)
	
	    
	    if(r.status_code == 201):
    		str1=now+"\t"+ip+"在"+ip3+"设备中封禁成功\n"
    		print(str1)
    		logs(str1)
	    else:
		#logs(data_json)
    		#str2=ip+"\t封禁失败!\n"
		str2=now+"\t"+ip+"封禁失败\n"
    		print(str2)
    		logs(str2)
#日志记录
def logs(date):
	f=open('log/new_elk.log','a+')
	f.write(date)

#攻击事件记录到CSV中
def writeToCsv(Attack_list):
    localtime = time.strftime("%Y-%m-%d",time.localtime())
    try:
        for i in Attack_list:   
            f = open('./attack/%s-attack.csv'%localtime,'a+')
            f.write('%s,%s,%s,%s,%s\n'%(i['attck_time'],i['name'],i['host'],i['src_ip'],i['attack_type']))
            f.close()
    except Exception as e:
        print("Error:  %s"%e)

#将封禁的IP写入文本中
def writeBanToTxt(ban):
    f = open('./attack/ban.txt','a+')
    now = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    for i in ban:
        #print(i)
        f.write('%s\n'%i)
        #print('%s,\t%s\t正在封禁中，已经将此IP写入ban.txt'%(now,i))
	Write_str=now+",\t"+i+"\t正在封禁中,已经将此IP写入ban.txt\n"
	print(Write_str)
    f.close()


#解决在时间差异的情况下ip重复被ban的问题
def delSameBanIP(banIp):
    now = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    #for i in ban:
    f = open('./attack/ban.txt','r')
    allList = []
    newBanIp = []
    for i in f.readlines():
        allList.append(i.strip("\n"))
   # print(allList)
    f.close()
    for i in banIp:
        if i not in allList:
            newBanIp.append(i)
        #print(newBanIp)
	else:
	    Ban_str=now+",\t"+i+",\t此IP已经封禁，无需再次封禁"
	    print(Ban_str)
    return newBanIp


def main():
    while True:
	print("elk Start.......")
        #获取日志为高的告警list
        attackList = esRequest()
        #提取list中的攻击源ip list型
        srcIp = choiceSrc_ip(attackList)
        #筛选可以封禁的ip list型
        banIp = choiceBanList(srcIp)
        # #记录攻击事件
        writeToCsv(attackList)
        # #筛选去重
        newIp = delSameBanIP(banIp)
        # #记录要禁用的ip
        writeBanToTxt(newIp)
        #封禁ip
        fuckipv4(newIp,headers)
        time.sleep(120)

if __name__ == '__main__':
    main()




