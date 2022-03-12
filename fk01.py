# coding=utf8
import requests
import sys
import time
#import datetime
from datetime import datetime,timedelta
from elasticsearch_dsl import Search,Q
from elasticsearch_dsl.query import MultiMatch,Match
import json
import ssl
import socket
import logging
import logging.handlers
from requests.packages.urllib3.exceptions import InsecureRequestWarning
requests.packages.urllib3.disable_warnings(InsecureRequestWarning)
reload(sys)
sys.setdefaultencoding('utf-8')


listB = []
listC=[]
list_groups=['FK01_2020-08-14_IP','FK01_2020-08-15_IP','FK01_2020-08-16_IP','FK01_2020-08-17_IP','FK01_2020-08-18_IP','FK01_2020-08-19_IP','FK01_2020-08-20_IP','FK01_2020-08-21_IP','FK01_2020-08-22_IP','FK01_2020-08-23_IP','FK01_2020-08-24_IP','FK01_2020-08-25_IP','FK01_2020-08-26_IP','FK01_2020-08-27_IP','FK01_2020-08-28_IP','FK01_2020-08-29_IP','FK01_2020-08-30_IP','FK01_2020-08-31_IP','FK01_2020-09-01_IP']


headers = {
            "Accept": "application/json",
            "Content-Type": "application/json",
            "Authorization": "Basic YWRtaW46Skp5aF9FRFNAV2g5NjIwMg=="
        }

#logger=logging.getLogger('fk01')
#fh=logging.handlers.SysLogHandler(('10.19.202.75',514),logging.handlers.SysLogHandler.LOG_AUTH)
#formatter=logging.Formatter('%(asctime)s-%(name)s-%(levelname)s-%(message)s')
#fh.setFormatter(formatter)
#logger.addHandler(fh)

def syslog(message):
    sock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    type = 'fk01'
    now = time.strftime("%Y-%m-%d %H:%M:%S", time.localtime())
    data = '%s %s %s' % (str(now),type, message)
    sock.sendto(data,('10.19.202.75', 514))
    sock.close()


#def Save_log(msg):
#	print(msg)
#        logger.info(msg)

# 筛选可以封禁的IP

# 筛选可以封禁的IP
def choiceBanList(src_ip):
    ban = []
    num = 0
    for ip in src_ip:
        ipA = src_ip[num].split('.')[0]
        ipB = src_ip[num].split('.')[0]+'.'+src_ip[num].split('.')[1]+'.0.0'
        ipC = src_ip[num].split('.')[0]+'.'+src_ip[num].split('.')[1]+'.'+src_ip[num].split('.')[2]+'.0'
        num += 1
        if(ipA == '10'):
            continue
        elif(ipB in listB):
            continue
        elif(ipC in listC):
            continue
        else:
            print('%s\tis attack ip, we will ban it !! '%ip)
            ban.append(ip)
            continue
    return ban


#获取源IP
def test_login():
	ssl._create_default_https_context=ssl._create_unverified_context
	url="https://172.17.242.65:9443/rpc"
	headers = {
	            'Host': '172.17.242.65:9443',
				'Connection': 'close',
				'Content-Length': '114',
				'Accept': 'application/json, text/plain, */*',
				'Origin': 'https://172.17.242.65:9443',
				'User-Agent': 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36',
				'Content-Type': 'application/json;charset=UTF-8',
				'Referer': 'https://172.17.242.65:9443/attack/ip/',
				'Accept-Language': 'zh-CN,zh;q=0.9',
				'Cookie': 'sessionid=0543a097384f47e6a04bcbfe544059b7'
	        }
	data='{"method":"RequestLogService.GetSrcIPAttackAnalysis","params":{"count":10000,"offset":0},"jsonrpc":"2.0","id":"0"}'

	response=requests.post(url,data = data,headers = headers,verify = False,timeout=15)
	x = json.loads(response.text)
 	list=[]
	list_addr=[]
	list_type=[]
	for hit in x["result"]["data"]:
		src_ip=hit["src_ip"]
		src_location=hit["location"]
		src_type=str(hit["type"])
		src_attack_count=hit["attack_count"]
		src_ip_num=hit["ip_num"]
		src_last_attack=hit["last_attack"]
		src_last_attack_time=hit["last_attack_time"]

		list.append(str(src_ip))

                msg = "{'src_ip':" + src_ip + ",'location':" + str(src_location) + ",'type':" + str(src_type) + ",'attack_count':" + str(src_attack_count) + ",'ip_num':" + str(src_ip_num) + ",'last_attack':" + str(src_last_attack) + ",'last_attack_time':" + str(src_last_attack_time) + "}"
	        msg_json = json.dumps(msg)
                syslog(msg_json)
        return list

#日志记录
def logs(data):
        f=open('log/new_fk01.log','a+')
        f.write(data)


#将封禁的IP写入文本中
def writeIptext(ban):
    f = open('./attack/ban.txt','a+')
    now = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    for i in ban:
        f.write('%s\n'%i)
    f.close()
    return 


#解决在时间差异的情况下ip重复被ban的问题
def delSameBanIP(banIp):
    now = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    f = open('./attack/ban.txt','r')
    allList = []
    newBanIp = []
    for i in f.readlines():
        allList.append(i.strip("\n"))
    f.close()
    for i in banIp:
        if i not in allList:
            newBanIp.append(i)
    return newBanIp

#封禁IP
def fuckipv4(ban,headers):
    print("feng IP...")
    print(ban)


    date_groups = ''
    now = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    dateNow = datetime.now().strftime('%Y-%m-%d')
    
    date_groups='FK01_'+dateNow+'_IP'
    print(date_groups)
    ip2 = ['172.17.226.89','172.17.242.89','172.17.225.88','172.17.241.88','172.22.9.17','172.22.9.18']
   # ip2=['172.22.9.17','172.22.9.18']
    for ip in ban:
        for ip3 in ip2:
            print("ban start....")
            url = 'http://%s/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafcustomv4wblist'%ip3
            data = {'mafcustomv4wblist':{'GroupStr':date_groups,'IPStart':'%s','IPEnd':'%s','LeftAge':'172800','Action':'2'}}
            data['mafcustomv4wblist']['IPStart'] = data['mafcustomv4wblist']['IPEnd'] = ip
            print(data)
	    data_json = json.dumps(data,encoding='utf-8')
            r = requests.post(url,data = data_json,headers = headers,verify = False)

	  
            if(r.status_code == 201):
                str1=now+"\t"+ip+"在"+ip3+"设备中封禁成功\n"
                print(str1)
                logs(str1)
            else:
		print(date_groups)
                #logs(data_json)
                #str2=ip+"\t封禁失败!\n"
                str2=now+"\t"+ip+"封禁失败\n"
                print(str2)
                logs(str2)

def nuwlist_num(EDS_ip):
	url='http://'+EDS_ip+'/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/getmafcustomv4wblistcount';
	headers={'Accept': 'application/json',
			'Content-Type': 'application/json',
			'Authorization': 'Basic YWRtaW46Skp5aF9FRFNAV2g5NjIwMg=='}
	data='';
	response=requests.get(url,headers=headers)
	x=json.loads(response.text)
	return x["getmafcustomv4wblistcount"]["Count"]
def eds_date_test(Eds_ip):
	offset=int(nuwlist_num(Eds_ip))-100
	#print(offset)

	num=0;
	list1=[]
	while num<offset:
		url="http://"+Eds_ip+"/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafcustomv4wblist?offset="+str(num)+"&count=200"
		#print url;
		headers={'Accept': 'application/json',
				'Content-Type': 'application/json',
				'Authorization': 'Basic YWRtaW46Skp5aF9FRFNAV2g5NjIwMg=='}
		data='';
		response=requests.get(url,headers=headers)
		x=json.loads(response.text)
		for hit in x["mafcustomv4wblist"]:
		 		src_ip=hit["IPaddr"]
		 		list1.append(str(src_ip))  #这里得反向输出
		num=num+100
	return list1


#获取所有的被封禁IP地址-获取六台eds数据 进行对比去重
def all_edslist():
	arr_eds=eds_date_test('172.17.226.89')+eds_date_test('172.17.242.89')+eds_date_test('172.17.225.88')+eds_date_test('172.17.241.88')+eds_date_test('172.22.9.17')+eds_date_test('172.22.9.18')
	new_arreds=list(set(arr_eds))
	#print("eds-----")
	leneds=len(new_arreds)
	list_all=['0','1']
	list_all[0]=new_arreds
	list_all[1]=leneds
	return list_all

#获取eds中的封禁数据
def eds_date():
	offset=int(nuwlist_num('172.17.242.89'))-100
	#print(offset)

	num=0;
	list1=[]
	while num<offset:
		url="http://172.17.242.89/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafcustomv4wblist?offset="+str(num)+"&count=200"
		#print url;
		headers={'Accept': 'application/json',
				'Content-Type': 'application/json',
				'Authorization': 'Basic YWRtaW46Skp5aF9FRFNAV2g5NjIwMg=='}
		data='';
		response=requests.get(url,headers=headers)
		x=json.loads(response.text)
		for hit in x["mafcustomv4wblist"]:
		 		src_ip=hit["IPaddr"]
		 		list1.append(str(src_ip))  #这里得反向输出
		num=num+100

	return list1



#获取从当前时间到7天前的FK01封禁数据
def test_login1():
	url="https://172.17.242.65:9443/rpc"
	headers = {
	            'Host': '172.17.242.65:9443',
				'Connection': 'close',
				'Content-Length': '114',
				'Accept': 'application/json, text/plain, */*',
				'Origin': 'https://172.17.242.65:9443',
				'User-Agent': 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36',
				'Content-Type': 'application/json;charset=UTF-8',
				'Referer': 'https://172.17.242.65:9443/attack/ip/',
				'Accept-Language': 'zh-CN,zh;q=0.9',
				'Cookie': 'sessionid=0543a097384f47e6a04bcbfe544059b7'
	        }

	#获取当前的时间戳
	day_one=str(datetime.today().now())
	day_new=day_one[0:19]
	timeArray=time.strptime(day_new,"%Y-%m-%d %H:%M:%S")
	end_time=int(time.mktime(timeArray))

	#获取7天前的时间戳
	day_two=str(datetime.today().now()-timedelta(days=1))
	day_newTwo=day_two[0:19]
	#print(day_newTwo)
	timeArray_two=time.strptime(day_newTwo,"%Y-%m-%d %H:%M:%S")
	start_time=int(time.mktime(timeArray_two))

	data='{"method":"RequestLogService.GetSrcIPAttackAnalysis","params":{"count":2000,"offset":0,"timestamp":[{"oper":"=","target":"%s-%s"}]},"jsonrpc":"2.0","id":"0"}'%(start_time,end_time)
        print(data)
	response=requests.post(url,data = data,headers = headers,verify = False,timeout=30)
	x = json.loads(response.text)


    #x = json.loads(response.text)  #诡异的代码

        list=[]
        list_addr=[]
        list_type=[]
	
        for hit in x["result"]["data"]:
                src_ip=hit["src_ip"]
                src_location=hit["location"]
                src_type=str(hit["type"])
                src_attack_count=hit["attack_count"]
                src_ip_num=hit["ip_num"]
                src_last_attack=hit["last_attack"]
                src_last_attack_time=hit["last_attack_time"]

                list.append(str(src_ip))

                msg = "{'src_ip':" + src_ip + ",'location':" + str(src_location) + ",'type':" + str(src_type) + ",'attack_count':" + str(src_attack_count) + ",'ip_num':" + str(src_ip_num) + ",'last_attack':" + str(src_last_attack) + ",'last_attack_time':" + str(src_last_attack_time) + "}"
                msg_json = json.dumps(msg)               
		syslog(msg_json)
        return list





#检测7天内的是否封禁
def mans():
	Eds_list=all_edslist()  #获取所有eds封禁的数据
	Fk01_list=choiceBanList(test_login1())  #7天的fk01的攻击源IP数据
	all_num=int(Eds_list[1])

	lista=[]
	#循环遍历用eds中的ip一对多的去对比fk01中的所有IP 如果有一个能对上返回成功否则失败
	for i in Fk01_list:
		nums=0
		for j in Eds_list[0]:
			if(str(i)==str(j)):
			 	print(i+"已经在eds中被封禁,无需再次封禁[YES]")
			 	break
			elif(nums>=all_num):
				break
			else:
				nums=nums+1
	  	if(nums>=all_num):
	  		#print(i+"此IP未被EDS封禁,正在执行封禁命令")	
			lista.append(i)

	fuckipv4(lista,headers)

if __name__ =="__main__":
	lastlist=[]
	while True:
	#	now = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
         #       logs(now + ',获取威胁情报中\n')
             #  list=test_login()
              # lastlist=delSameBanIP(list)
		#banIP=choiceBanList(lastlist)
		#writeIptext(banIP)
		#fuckipv4(banIP,headers)
               # print('新增IP: %s'%banIP)
		mans()
	
		#lista=eds_date()
		#print(lista)
		time.sleep(1800)


