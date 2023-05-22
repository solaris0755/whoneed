======================================
김반장 중복검사 z2.php
# 카카오톡에서 압축파일을 다운로드 받는다.
cd Download/kkk

# 한글명 자소 분리된것 수정한다.
find . -name "*.xlsx" -exec convmv -f utf-8 -t utf-8 --nfc --notest {} \;

# 만약 convmv 가 설치안되었다면 설치한다.
brew install convmv

mkdir -p ~/prj/excel_data/202209
rsync -av * ~/prj/excel_data/202209


# SQL
truncate table sal;

# php7 인지 확인한다.
php -v
brew link --overwrite --force php@7.4

# 스크립트를 실행한다.
php z2.php

# 결과를 TSV 형식으로 다운받는다.
select * from sal order by 1,2,3;
whoneed_sal.xlsx

# 중복검사를 해본다.
select name,d, count(1)
from sal
group by name,d
having count(1)>1
order by 3 desc

========================================================================
kim2.php

년월, 현장
    이름, 주민, 전화, 주소
    공수, 일수, 단가
    수당합계
    공제항목 6가지

create table salary
(
    num     int auto_increment
        primary key,
    ym      char(6)     not null,
    place   varchar(30) not null,
    mb_no   int         null,
    ilsoo int         null,
    gongsoo int         null,
    danga   int         null,
    sal     int         null,
    gab     int         null,
    ju      int         null,
    go      int         null,
    kuk     int         null,
    health  int         null,
    jang    int         null,
    realsal int         null
);
create table emp
(
    num   int auto_increment
        primary key,
    name  varchar(20)  not null,
    jumin varchar(20)  not null,
    addr  varchar(100) null,
    hp    varchar(20)  null,
    constraint idx1
        unique (jumin)
);

truncate table salary;
truncate table emp;


select
    s.ym 년월
     ,s.place 현장
     ,e.name 이름
     ,e.jumin 주민번호
     ,e.hp 연락처
     ,e.addr 주소
     ,ilsoo 일수
     ,gongsoo 공수
     , danga 단가
     , sal 지급
     , gab 갑근세
     , ju 주민번호
     , go 고용보험
     , kuk 국민연금
     , health 건강보험
     , jang 장기요양보험
     , realsal 실지급
from salary s
join emp e on e.num=s.mb_no
======================================
ERP 에서 데이터 추출
create table erp_price (
    num int auto_increment primary key ,
    ca1 varchar(50),
    ca2 varchar(50),
    place varchar(100),
    ym char(6),
    price int
);