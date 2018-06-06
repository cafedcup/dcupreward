<?php
	if(defined('SITE_NAME') == false){
		define('SITE_NAME','Test Project');
		//sign in page
		define('USR_NAME','ชื่อผู้ใช้');
		define('PWD','รหัสผ่าน');
		define('WRONG_USR_PWD','ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง');
		define('LOG_IN_EXPIRED','กรุณาเข้าระบบใหม่อีกครั้ง');
		define('SIGN_IN','เข้าใช้ระบบ');
		
		//header
		define('PWD_CHANGE','เปลี่ยน'.PWD);
		define('PREF','ตั้งค่าพื้นฐานของระบบ');
		define('PROFILE','Profile');
		define('SIGN_OUT','ออกจากระบบ');
		define('BACKEND','ระบบจัดการข้อมูล');
		define('WELCOME','ยินดีต้อนรับ');
		
		//change password
		define('OLD_PWD','รหัสผ่านปัจจุบัน');
		define('NEW_PWD','รหัสผ่านใหม่');
		define('CONFIRM_PWD','ยืนยัน'.PWD);
		define('PWD_LENGTH','(ไม่ต่ำกว่า 6 ตัวอักษร)');
		define('ERR_OLD_PWD',OLD_PWD.'ไม่ถูกต้อง');
		define('ERR_OLD_PWD_EMPTY','กรุณาระบุ'.OLD_PWD);
		define('ERR_PWD_NOT_MATCH','กรุณาระบุรหัสผ่านใหม่ให้เหมือนกัน');
		define('ERR_PWD_LENGTH','รหัสผ่านควรมีความยาวอย่างน้อย 6 ตัวอักษร');	
		
		//preference
		define('ADMIN_EMAIL','อีเมล์ สำหรับ Admin');
		define('CT_EMAIL','อีเมล์สำหรับติดต่อ');
		define('SITE_CUR','สกุลเงิน');
		define('ADDR','ที่อยู่');
		define('P_CODE','รหัสไปรษณีย์');
		define('MOBILE','มือถือ');
		define('PHONE','โทรศัพท์');
		define('FAX','แฟกซ์');
		
		//paginate word
		define('FIRST_WORD','First');
		define('PREV_WORD','Previous');
		define('NEXT_WORD','Next');
		define('LAST_WORD','Last');
		
		//button
		define('ADD','เพิ่ม');
		define('VIEW','View');
		define('UPDATE','แก้ไข');
		define('DEL','ลบ');
		define('CLK_ACT','เลือกใช้งาน');
		define('CLK_DEACT','ระงับการใช้');
		define('SAVE','จัดเก็บ');
		define('BACK','ย้อนกลับ');
		define('MORE_INFO','ข้อมูลเพิ่มเติม');
		define('ACT','Action');
		define('CHK_ALL','เลือกทั้งหมด');
		define('CHK','ตรวจสอบ');
		define('SRCH','ค้นหา');
		define('CLR','Clear');
		define('REPLY','ข้อความตอบกลับ');
		define('SEND_REPLY','ส่ง'.REPLY);
		define('CLS','ปิดหน้านี้');
		
		//status
		define('ACTIVE','ใช้งาน');
		define('INACTIVE','ระงับ');
		
		//menu
		define('SETTING','ตั้งค่า');
		define('PWD_SET','เปลี่ยนรหัสผ่าน');
		define('FE_TXT','ตั้งค่าข้อความบนเว็บ');
		define('CT_MNG','เปลี่ยนแปลงเนื้อหา');
		define('PROV','จังหวัด');
		define('COM','บริษัท');
		define('JOB','ตำแหน่งงาน');
		define('WF_JOB','ตำแหน่งงานฟรีที่รออนุมัติ');
		define('F_JOB','ตำแหน่งงานฟรีที่อนุมัติแล้ว');
		define('NEWS_CAT','ประเภทข่าว');
		define('NEWS','ข่าว');
		define('WB','เว็บบอร์ด');
		
		//general
		define('IP','IP Address');
		define('WEBSITE','เว็บไซต์');
		define('MENU_NAME','ชื่อเมนู');
		define('LANG','ภาษา');
		define('LANG_1','ไทย');
		define('LANG_2','English');
		define('LOAD','กำลังดำเนินการ...');
		define('REQ_FIELD','* ข้อมูลที่ต้องระบุ');
		define('GEN_INFO','ข้อมูลทั่วไป');
		define('ID','รหัส');
		define('NAME','ชื่อ');
		define('TITLE','หัวข้อ');
		define('INTRO','คำอธิบายพอสังเขป');
		define('LONG_INTRO','คำอธิบายพอสังเขปแบบยาว');
		define('DESCRIP','รายละเอียด');
		define('FNAME','ชื่อ');
		define('LNAME','นามสกุล');
		define('EMAIL','อีเมล์');
		define('YES','ใช่');
		define('NO','ไม่ใช่');
		define('STATUS','สถานะ');
		define('RM','หมายเหตุ');
		define('IN_RM','หมายเหตุส่วนตัว');
		define('AVAIL','สามารถใช้ได้');
		define('URL_RW','URL Rewrite');
		define('NOT_AVAIL','ไม่สามารถใช้ได้');
		define('META_AUTHOR','Meta Author');
		define('META_TITLE','Meta Title');
		define('META_KEYWORD','Meta Keyword');
		define('META_DESCRIP','Meta Description');
		define('MAX_VALUE_STR','ค่าสูงสุด: ');
		define('MAX_CHAR','จำนวนตัวอักษรไม่เกิน');
		define('DEL_CF','ยืนยันที่จะลบข้อมูลนี้?');
		define('PLS_SLT',' -- กรุณาเลือกข้อมูล -- ');
		define('IMG','รูปภาพ');
		define('IMG_T','ประเภท'.IMG);
		define('IMG_DIMEN','ขนาดของ'.IMG);
		define('DP_OD','แสดงในลำดับที่');
		define('SHOW','แสดง');
		define('EX','ตัวอย่าง');
		define('IN_DATE','วันที่เพิ่มข้อมูล');
		define('COMP_ADD','เพิ่มข้อมูลเรียบร้อย');
		define('COMP_DEL','ลบข้อมูลเรียบร้อย');
		define('COMP_UD','แก้ไขข้อมูลเรียบร้อย');
		define('COMP_ACT','ข้อมูลถูกนำมาใช้งานแล้ว');
		define('COMP_DEACT','ระงับการใช้งานข้อมูลเรียบร้อยแล้ว');
		define('ERR_MAIL_FM','รูปแบบอีเมล์ไม่ถูกต้อง');
		define('ERR_NAME_EMPTY','กรุณาระบุ'.NAME);
		define('ERR_NAME_DUPLICATE',NAME.'ไม่สามารถซ้ำกับข้อมูลอื่นได้');
		define('ERR_TITLE_EMPTY','Title cannot be empty.');
		define('ERR_REQ_INFO','กรุณาระบุข้อมูลสำคัญให้ครบถ้วน');
		define('ERR_NOT_ENOUGH_INFO','ข้อมูลไม่ครบถ้วน');
		define('ERR_INFO_INUSE','ข้อมูลนี้มีการใช้งานอยู่');
		define('ERR_URL_RW_EMPTY','กรุณาระบุข้อมูลของ '.URL_RW);
		define('ERR_URL_RW_INUSE','ไม่สามารถใช้ '.URL_RW.' นี้ได้');
		define('ERR_SLECT_ONE','กรุณาเลือกอย่างน้อย 1 ตัวเลือก');
		define('ERR_DATE','รูปแบบวันที่ไม่ถูกต้อง');
		
		//company
		define('LOGO_IMG','ภาพ logo');
		define('COM_DESCIIP','ข้อมูลเกี่ยวกับบริษัท');
		define('COM_TYPE','ประเภทกิจการ');
		define('CT_NAME','ชื่อผู้ติดต่อ');
		define('JOB_NAME','ตำแหน่งงาน');
		define('JOB_QTT','จำนวนอัตรา');
		define('JOB_WF','สวัสดิการ');
		define('EDU_LV','ระดับการศึกษา');
		define('W_TYPE','เวลาทำงาน');
		define('SLR','เงินเดือนโดยประมาณ');
		define('JOB_DESCRIP','คุณสมบัติและลักษณะงาน');
	
	}
?>