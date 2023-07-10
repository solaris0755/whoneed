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
kim3.php

create table g5_emp
(
    mb_no   int auto_increment primary key,
    name  varchar(20)  not null,
    jumin varchar(20)  not null,
    addr  varchar(100) null,
    hp    varchar(20)  null,
    constraint idx1 unique (jumin)
);
drop table g5_sal;
create table g5_sal(
    num     int auto_increment   primary key,
    ym      char(6)     not null,
    place   varchar(30) not null,
    mb_no   int         null,
    d1 decimal(2,1),
    d2 decimal(2,1),
    d3 decimal(2,1),
    d4 decimal(2,1),
    d5 decimal(2,1),
    d6 decimal(2,1),
    d7 decimal(2,1),
    d8 decimal(2,1),
    d9 decimal(2,1),
    d10 decimal(2,1),
    d11 decimal(2,1),
    d12 decimal(2,1),
    d13 decimal(2,1),
    d14 decimal(2,1),
    d15 decimal(2,1),
    d16 decimal(2,1),
    d17 decimal(2,1),
    d18 decimal(2,1),
    d19 decimal(2,1),
    d20 decimal(2,1),
    d21 decimal(2,1),
    d22 decimal(2,1),
    d23 decimal(2,1),
    d24 decimal(2,1),
    d25 decimal(2,1),
    d26 decimal(2,1),
    d27 decimal(2,1),
    d28 decimal(2,1),
    d29 decimal(2,1),
    d30 decimal(2,1),
    d31 decimal(2,1),
    공수 decimal(10,1),
    일수 decimal(10,1),
    단가 int,
    금액 int,
    수당 int,
    합계 int,
    갑근세 int,
    주민세 int,
    고용보험 int,
    국민연금 int,
    건강보험 int,
    장기요양보험 int,
    공제합계 int,
    최종합계 int,
    constraint idx1 unique (ym,place,mb_no)
);

truncate table g5_emp;
truncate table g5_sal;
select
     place
     , ym
     , e.name
     , e.jumin
     , d1
     , d2
     , d3
     , d4
     , d5
     , d6
     , d7
     , d8
     , d9
     , d10
     , d11
     , d12
     , d13
     , d14
     , d15
     , d16
     , d17
     , d18
     , d19
     , d20
     , d21
     , d22
     , d23
     , d24
     , d25
     , d26
     , d27
     , d28
     , d29
     , d30
     , d31
     , 공수, 일수, 단가, 금액, 수당, 합계, 갑근세, 주민세, 고용보험, 국민연금, 건강보험, 장기요양보험, 공제합계, 최종합계
from g5_sal a
join g5_emp e on e.mb_no=a.mb_no
order by place, e.jumin, ym;

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
