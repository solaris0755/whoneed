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
