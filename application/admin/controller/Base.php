<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Db;

class Base extends Controller
{
    public function __construct(){
        parent::__construct();
        $id=session('user')->id;
        if(!$id)
            $this->redirect('/index.php?s=admin/login/index');
        $privilege = model('privilege');
        if(strtolower(request()->module()).'/'.strtolower(request()->controller()).'/'.strtolower(request()->action())!='admin/index/index'){
            //echo request()->module().'/'.request()->controller().'/'.request()->action();die;
            if(!$privilege->hasPri())
            $this->error('无权访问！');
        }
        
        //获取菜单
        $menu=$privilege->getMenu();
        $this->assign('menu',$menu);
    }
    public function getCity(Request $request){
        //获取数据
        $data=$request->param();
        $city='{"安徽":["合肥市","芜湖市","蚌埠市","淮南市","马鞍山市","淮北市","铜陵市","安庆市","黄山市","阜阳市","宿州市","滁州市","六安市","宣城市","池州市","亳州市"],"北京":["北京"],"福建":["福州市","莆田市","泉州市","厦门市","漳州市","龙岩市","三明市","南平市","宁德市"],"上海":["上海"],"天津":["天津"],"山西":["太原","大同","阳泉","长治","晋城","朔州","晋中","运城","忻州","临汾","吕梁市"],"贵州":["贵阳市","六盘水市","遵义市","铜仁市","黔西南州","毕节市","安顺市","黔东南州","黔南州"],"河北":["石家庄","唐山市","秦皇岛市","邯郸市","邢台市","保定市","张家口市","承德市","沧州市","廊坊市","衡水市"],"浙江":["杭州市","宁波市","温州市","绍兴市","湖州市","嘉兴市","金华市","衢州市","台州市","丽水市","舟山市"],"江苏":["南京市","无锡市","徐州市","常州市","苏州市","南通市","连云港市","淮安市","盐城市","扬州市","镇江市","泰州市","宿迁市"],"甘肃":["兰州市","嘉峪关市","金昌市","白银市","天水市","酒泉市","张掖市","武威市","定西市","陇南市","平凉市","庆阳市","临夏回族自治州","甘南藏族自治州"],"四川":["成都市","绵阳市","自贡市","攀枝花市","泸州市","德阳市","广元市","遂宁市","内江市","乐山市","资阳市","宜宾市","南充市","达州市","雅安市","阿坝藏族羌族自治州","甘孜藏族自治州","凉山彝族自治州","广安市","巴中市","眉山市"],"西藏":["拉萨","昌都市","山南","日喀则市","那曲","阿里","林芝"],"云南":["昆明市","曲靖市","玉溪市","昭通市","保山市","丽江市","普洱市","临沧市","德宏傣族景颇族自治州","怒江傈僳族自治州","迪庆藏族自治州","大理白族自治州","楚雄彝族自治州","红河哈尼族彝族自治州","文山壮族苗族自治州","西双版纳傣族自治州"],"重庆":["重庆"],"青海":["西宁市","海东市","海北藏族自治州","黄南藏族自治州","海南藏族自治州","果洛藏族自治州","玉树藏族自治州","海西蒙古族藏族自治州"],"新疆":["乌鲁木齐","克拉玛依","吐鲁番市","哈密市","阿克苏地区","喀什地区","和田地区","昌吉回族自治州","博尔塔拉蒙古自治州","巴音郭楞蒙古自治州","克孜勒苏柯尔克孜自治州","伊犁哈萨克自治州","石河子","阿拉尔市","图木舒克","五家渠","北屯","铁门关","双河","可克达拉","昆玉"],"河南":["郑州市","开封市","洛阳市","平顶山市","安阳市","鹤壁市","新乡市","焦作市","濮阳市","许昌市","漯河市","三门峡市","商丘市","周口市","驻马店市","南阳市","信阳市","济源市"],"湖南":["长沙市","株洲市","湘潭市","衡阳市","邵阳市","岳阳市","张家界市","益阳市","常德市","娄底市","郴州市","永州市","怀化市","湘西土家族苗族自治州"],"吉林":["长春","吉林市","四平市","辽源市","通化市","白山市","松原市","白城市","延边朝鲜族自治州"],"江西":["南昌市","九江市","上饶市","抚州市","宜春市","吉安市","赣州市","景德镇市","萍乡市","新余市","鹰潭市"],"辽宁":["沈阳","大连","鞍山","抚顺","本溪","丹东","锦州","营口","阜新","辽阳","盘锦","铁岭","朝阳","葫芦岛"],"山东":["济南市","青岛市","淄博市","枣庄市","东营市","烟台市","潍坊市","济宁市","泰安市","威海","日照","滨州","德州","聊城","临沂","菏泽","莱芜市"],"陕西":["西安市","宝鸡市","咸阳市","渭南市","铜川市","延安市","榆林市","安康市","汉中市","商洛市","杨凌示范区"],"广西":["南宁市","柳州市","桂林市","梧州市","北海市","南宁地区","柳州地区","桂林地区","贺州市","玉林市","百色市","河池市","钦州市","防城港市","贵港市"],"宁夏":["银川","石嘴山","吴忠市","固原市","中卫市"],"黑龙江":["哈尔滨","齐齐哈尔","牡丹江","佳木斯","大庆","伊春","鸡西","鹤岗","双鸭山","七台河","绥化","黑河","大兴安岭"],"内蒙古":["呼和浩特","包头市","乌海市","赤峰市","呼伦贝尔市","兴安盟","通辽市","锡林郭勒盟","乌兰察布盟","鄂尔多斯市","巴彦淖尔盟","阿拉善盟"],"海南":["海口","三亚市","琼北地区","琼南地区","洋浦开发区","三沙市"],"湖北":["武汉","黄石","十堰市","荆州市","宜昌市","襄阳市","鄂州市","荆门市","黄冈市","孝感市","咸宁市","仙桃市","潜江市","神农架","恩施州","天门市","随州市"],"广东":["广州市","深圳市","珠海市","汕头市","佛山市","韶关市","湛江市","肇庆","江门","茂名","惠州","梅州","汕尾","河源 ","江阳","清远","东莞市","中山市","潮州市","揭阳市","云浮市"]}';
        $city=json_decode($city,true);
        return sendJson(1,'',0,['city'=>$city[$data['province']]]);
    }
    public function getArea(Request $request){
        //获取数据
        $data=$request->param();
        $city='{"安徽_合肥市":["瑶海区","庐阳区","蜀山区","包河区","巢湖市","长丰县","肥东县","肥西县","庐江县"],"安徽_芜湖市":["镜湖区","戈江区","鸠江区","三山区","芜湖县","繁昌县","南陵县","无为县"],"安徽_蚌埠市":["龙子湖区","蚌山区","禹会区","淮上区","五河县","固镇县","怀远县"],"安徽_淮南市":["大通区","田家庵区","谢家集区","八公山区","潘集区","凤台县","寿县"],"安徽_马鞍山市":["花山区","雨山区","博望区","含山县","和县","当涂县"],"安徽_淮北市":["相山区","烈山区","杜集区","濉溪县"],"安徽_铜陵市":["铜官区","郊区","义安区","枞阳县"],"安徽_安庆市":["迎江区","大观区","宜秀区","怀宁县","桐城市","潜山县","太湖县","宿松县","望江县","岳西县"],"安徽_黄山市":["屯溪区","黄山区","徽州区","歙县","休宁县","黟县","祁门县"],"安徽_阜阳市":["颍州区","颍泉区","颍东区","颍上县","界首市","临泉县","阜南县","太和县"],"安徽_宿州市":["埇桥区","萧县","砀山县","灵璧县","泗县"],"安徽_滁州市":["琅琊区","南谯区","天长市","明光市","全椒县","来安县","凤阳县","定远县"],"安徽_六安市":["金安区","裕安区","叶集区","霍邱县","霍山县","金寨县","舒城县"],"安徽_宣城市":["宣州区","郎溪县","广德县","宁国市","泾县","绩溪县","旌德县"],"安徽_池州市":["贵池区","青阳县","石台县","东至县"],"安徽_亳州市":["谯城区","蒙城县","涡阳县","利辛县"],"北京_北京":["东城区","西城区","朝阳区","丰台区","石景山区","海淀区","门头沟区","房山区","通州区","顺义区","昌平区","大兴区","怀柔区","平谷区","密云区","延庆区"],"福建_福州市":["鼓楼区","台江区","仓山区","马尾区","晋安区","连江县","闽侯县","永泰县","闽清县","罗源县","平潭县","福清市","长乐市"],"福建_莆田市":["荔城区","城厢区","涵江区","秀屿区","仙游县"],"福建_泉州市":["丰泽区","鲤城区","洛江区","泉港区","石狮市","晋江市","南安市","惠安县","安溪县","永春县","德化县","金门县"],"福建_厦门市":["同安区","翔安区","集美区","海沧区","湖里区","思明区"],"福建_漳州市":["芗城区","龙文区","龙海市","漳浦县","云霄县","东山县","诏安县","平和县","南靖县","长泰县","华安县"],"福建_龙岩市":["新罗区","漳平市","长汀县","连城县","上杭县","武平县","永定县"],"福建_三明市":["永安市","三元区","梅列区","明溪县","清流县","宁化县","大田县","尤溪县","沙县","将乐县","泰宁县","建宁县"],"福建_南平市":["延平区","建瓯市","建阳市","邵武市","武夷山市","松溪县","浦城县","光泽县","政和县","顺昌县"],"福建_宁德市":["蕉城区","福安市","福鼎市","寿宁县","霞浦县","柘荣县","屏南县","古田县","周宁县"],"上海_上海":["黄浦区","徐汇区","长宁区","静安区","普陀区","虹口区","杨浦区","浦东新区","闵行区","宝山区","嘉定区","金山区","松江区","青浦区"],"天津_天津":["和平区","河北区","河东区","河西区","南开区","红桥区","东丽区","西青区","津南区","北辰区","武清区","宝坻区","静海区","宁河区","蓟州区","滨海区"],"山西_太原":["迎泽区","杏花岭区","万柏林区","尖草坪区","小店区","晋源区","清徐县","阳曲县","娄烦县","古交市"],"山西_大同":["城区","矿区","南郊区","新荣区","左云县","大同县","天镇县","浑源县","广灵县","灵丘县","阳高县"],"山西_阳泉":["城区","矿区","郊区","孟县","平定县"],"山西_长治":["城区","郊区","长治县","襄垣县","屯留县","平顺县","黎城县","壶关县","长子县","武乡县","沁县","沁源县","潞城市"],"山西_晋城":["城区","泽州县","阳城县","陵川县","泌水县","高平市"],"山西_朔州":["朔城区","平鲁区","山阴县","应县","怀仁县","右玉县"],"山西_晋中":["榆次县","榆社县","左权县","和顺县","昔阳县","寿阳县","太谷县","祁县","平遥县","灵石县","介休市"],"山西_运城":["盐湖区","临猗县","芮城县","万荣县","新绛县","稷山县","闻喜县","夏县","绛县","平陆县","垣曲县","河津市","永济市"],"山西_忻州":["忻府区","定襄县","五台县","代县","繁峙县","宁武县","静乐县","神池县","五寨县","岢岚县","偏关县","河曲县","保德县","原平市"],"山西_临汾":["尧都区","曲沃县","翼城县","襄汾县","洪洞县","古县","浮山县","吉县","乡宁县","蒲县","大宁县","永和县","汾西县","隰县","安泽县","侯马市","霍州市"],"山西_吕梁市":["离石区","文水县","交城县","兴县","临县","柳林县","岚县","石楼县","交口县","方山县","中阳县","孝义市","汾阳市"],"贵州_贵阳市":["云岩区","南明区","花溪区","乌当区","白云区","小河区","清镇市","修文县","息峰县","开阳县"],"贵州_六盘水市":["钟山区","水城县","六技特区","盘县"],"贵州_遵义市":["红花岗区","江川区","赤水市","遵义县","仁怀市","绥阳县","桐梓县","习水县","湄潭县","风冈县","余庆县","正安县","务川县","道真县"],"贵州_铜仁市":["碧江区","思南县","江口县","石矸县","德江县","万山区","印江县","沿河县","玉屏县","松桃县"],"贵州_黔西南州":["兴义市","兴仁县","晴隆县","普安县","册亨县","望谟县","安龙县","贞丰县"],"贵州_毕节市":["七星关区","赫章县","纳雍县","黔西县","大方县","织金县","金沙县","威宁县"],"贵州_安顺市":["西秀区","平坝区","普定县","关岭县","镇宁县","紫云县"],"贵州_黔东南州":["凯里市","麻江县","黄平县","施秉县","三穗县","天柱县","锦屏县","黎平县","从江县","榕江县","丹寨县","岑巩县","雷山县","台江县","剑河县","镇远县"],"贵州_黔南州":["都匀市","龙里县","贵定县","福泉县","瓮安县","独山县","平塘县","荔波县","惠水县","长顺市","罗甸县","三都县","贵安新区"],"河北_石家庄":["新华区","桥西区","长安区","裕华区","矿区","藁城区","鹿泉区","栾城区","晋州市","新乐市","正定县","深泽县","无极县","赵县","高邑县","元氏县","赞皇县","井陉县","平山县","灵寿县","行唐县","辛集市","高新区"],"河北_唐山市":["路北区","路南区","丰南区","开平区","丰润区","古冶区","迁西县","滦县","滦南县","玉田县","乐亭县","唐海县","迁安市","遵化市"],"河北_秦皇岛市":["北戴河","山海关","海港区","抚宁县","昌黎县","卢龙县","青龙县"],"河北_邯郸市":["丛台区","复兴区","邯山区","峰峰矿区","武安市","鸡泽县","邱县","永年县","曲周县","邯郸县","肥乡县","馆陶县","涉县","广平县","成安县","魏县","磁县","临漳县","大名县"],"河北_邢台市":["桥东区","桥西区","沙河市","南宫市","清河县","宁晋县","内丘县","广宗县","邢台县","任县","临西县","新河县","隆尧县","柏乡县","威县","临城县","平乡县","南和县","巨鹿县"],"河北_保定市":["定州市","涿州市","安国市","高碑店市","易县","徐水县","涞源县","定兴县","顺平县","唐县","望都县","涞水县","清苑县","满城县","高阳县","安新县","雄县","容城县","曲阳县","阜平县","蠡县","博野县","南市区","北市区","新市区"],"河北_张家口市":["桥西区","桥东区","宣化区","下花园区","宣化县","康保县","沽源县","尚义县","蔚县","阳原县","怀安县","万全县","怀来县","涿鹿县","赤城县","崇礼县","张北县"],"河北_承德市":["双桥区","双滦区","鹰手营子矿区","宽城满族自治县","兴隆县","平泉县","滦平县","丰宁满族自治县","隆化县","围场满族蒙古族自治县","承德县"],"河北_沧州市":["新华区","运河区","泊头市","任丘区","黄骅市","河间市","献县","吴桥县","沧县","东光县","肃宁县","南皮县","盐山县","青县","海兴县","孟村回族自治县"],"河北_廊坊市":["安次区","广阳区","三河市","霸州市","香河县","固安县","永清县","文安县","大城县","大厂回族自治县"],"河北_衡水市":["桃城区","滨湖新区","冀州市","深州市","武强县","饶阳县","安平县","故城县","景县","阜城县","枣强县","武邑县"],"浙江_杭州市":["上城区","下城区","江干区","拱墅区","西湖区","滨江区","萧山区","余杭区","富阳区","桐庐区","建德区","临安区","淳安区"],"浙江_宁波市":["海曙区","江东区","江北区","北仑区","镇海区","鄞州区","余姚市","慈溪市","奉化市","象山县","宁海县"],"浙江_温州市":["鹿城区","龙湾区","瓯海区","洞头区","瑞安市","乐清市","永嘉县","平阳县","苍南县","泰顺县","文成县"],"浙江_绍兴市":["越城区","河桥区","上虞区","新昌县","嵊州市","诸暨市"],"浙江_湖州市":["吴兴区","南浔区","德清县","长兴县","安吉县"],"浙江_嘉兴市":["南湖区","秀洲区","嘉善县","海盐县","海宁市","平湖市","桐乡市"],"浙江_金华市":["婺城区","金东区","兰溪市","义乌市","东阳市","永康市","浦江县","武义县","磐安县"],"浙江_衢州市":["柯城区","衢江区","江山市","龙游县","常山县","开化县"],"浙江_台州市":["椒江区","黄岩区","路桥区","临江市","温岭市","玉环县","天台县","仙居县","三门县"],"浙江_丽水市":["莲都区","龙泉市","青田县","缙云县","遂昌县","松阳县","云和县","庆元县","景宁畲族自治县"],"浙江_舟山市":["定海区","普陀区","岱山县","嵊泗县"],"江苏_南京市":["玄武区","秦淮区","鼓楼区","建邺区","雨花台区","栖霞区","浦口区","六合区","江宁区","溧水区","高淳区"],"江苏_无锡市":["梁溪区","滨湖区","惠山区","锡山区","新吴区","江阴市","宜兴市"],"江苏_徐州市":["云龙区","鼓楼区","贾汪区","泉山区","铜山区","新沂市","邳州市","睢宁县","沛县","丰县"],"江苏_常州市":["天宁区","钟楼区","新北区","武进区","金坛区","溧阳市"],"江苏_苏州市":["姑苏区","相城区","吴中区","虎丘区","吴江区","常熟市","昆山市","张家港市","太仓市"],"江苏_南通市":["崇川区","港闸区","通州区","海安县","如东县","如皋市","海门市","启东市"],"江苏_连云港市":["海州区","连云区","赣榆区","灌云县","灌南县","东海县"],"江苏_淮安市":["清河区","清浦区","淮安区","淮阴区","涟水区","洪泽县","盱眙县","金湖县"],"江苏_盐城市":["东台市","亭湖区","大丰区","盐都区","响水县","滨海县","阜宁县","射阳县","建湖县"],"江苏_扬州市":["高邮市","仪征市","广陵区","邗江区","江都区","宝应县"],"江苏_镇江市":["丹阳市","扬中市","京口区","句容市","丹徒区","润州区","镇江新区"],"江苏_泰州市":["泰兴市","兴化市","靖江市","海陵区","高港区","姜堰区"],"江苏_宿迁市":["洋河新区","宿豫区","宿城区","沭阳县","泗阳县","泗洪县"],"甘肃_兰州市":["城关区","七里河区","西固区","安宁区","红古区","永登县","榆中县"],"甘肃_嘉峪关市":[""],"甘肃_金昌市":["金川区","永昌县"],"甘肃_白银市":["白银区","平川区","靖远县","景泰县","会宁县"],"甘肃_天水市":["秦州区","麦积区","武山县","甘谷县","清水县","秦安县","张家川回族自治县"],"甘肃_酒泉市":["肃州区","玉门市","敦煌市","瓜州县","金塔县","阿克塞县"],"甘肃_张掖市":["甘州区","民乐县","山丹县","临泽县","高台县","肃南裕固族自治县"],"甘肃_武威市":["凉州区","古浪县","天祝县","民勤县"],"甘肃_定西市":["安定区","岷县","渭源县","陇西县","通渭县","漳县","临洮县"],"甘肃_陇南市":["武都区","成县","礼县","康县","文县","两当县","徽县","宕昌县","西和县"],"甘肃_平凉市":["崆峒区","灵台县","静宁县","崇信县","华亭县","泾川县","庄浪县"],"甘肃_庆阳市":["西峰区","庆城县","镇原县","合水县","华池县","环县","宁县","正宁县"],"甘肃_临夏回族自治州":["临夏市","临夏县","康乐县","永靖县","广河县","和政县","东乡族自治县","积石山县"],"甘肃_甘南藏族自治州":["合作市","临潭县","卓尼县","舟曲县","迭部县","玛曲县","碌曲县","夏河县"],"四川_成都市":["武侯区","锦江区","青羊区","金牛区","成华区","龙泉驿区","温江区","新都区","青白江区","双流区","郫县","浦江县","大邑县","金堂县","新津县","都江堰市","彭州市","邛崃市","崇州市","简阳市"],"四川_绵阳市":["涪城区","游仙区","安州区","梓潼县","三台县","盐亭县","江油市","北川羌族自治县"],"四川_自贡市":["自流井区","贡井区","大安区","沿滩区","荣县","富顺县"],"四川_攀枝花市":["东区","西区","仁和区","米易县","盐边县"],"四川_泸州市":["江阳区","龙马潭区","纳溪区","泸县","合江县","叙永县","古蔺县"],"四川_德阳市":["旌阳区","广汉市","什邡市","绵竹市","中江县","罗江县"],"四川_广元市":["利州区","昭化区","朝天区","旺苍县","青川县","剑阁县","苍溪县"],"四川_遂宁市":["船山区","安居区","射洪县","蓬溪县","大英县"],"四川_内江市":["市中区","东兴区","资中县","隆昌县","威远县"],"四川_乐山市":["市中区","沙湾区","五通桥区","金口河区","犍为县","井研县","夹江县","沐川县","峨眉山市","峨边彝族自治县","马边彝族自治县"],"四川_资阳市":["雁江区","安岳县","乐至县"],"四川_宜宾市":["翠屏区","南溪区","宜宾县","江安县","长宁县","高县","筠连县","珙县","兴文县","屏山县"],"四川_南充市":["顺庆区","高坪区","嘉陵区","西充县","南部县","蓬安县","营山县","仪陇县","阆中市"],"四川_达州市":["通川区","达川区","宣汉县","开江县","大竹县","渠县","万源市"],"四川_雅安市":["雨城区","名山区","荥经县","汉源县","石棉县","天全县","芦山县","宝兴县"],"四川_阿坝藏族羌族自治州":["马尔康市","金川县","小金县","阿坝县","若尔盖县","红原县","壤塘县","汶川县","理县","茂县","松潘县","九寨沟县","黑水县"],"四川_甘孜藏族自治州":["康定市","泸定县","丹巴县","九龙县","雅江县","道孚县","炉霍县","甘孜县","新龙县","德格县","白玉县","石渠县","色达县","理塘县","巴塘县","乡城县","稻城县","得荣县"],"四川_凉山彝族自治州":["西昌市","德昌县","会理县","会东县","宁南县","普格县","布拖县","昭觉县","金阳县","雷波县","美姑县","甘洛县","越西县","喜德县","冕宁县","盐源县","木里藏族自治县"],"四川_广安市":["广安区","前锋区","邻水县","武胜县","岳池县","华蓥市"],"四川_巴中市":["巴州区","恩阳区","平昌县","通江县","南江县"],"四川_眉山市":["东坡区","彭山区","仁寿县","丹棱县","青神县","洪雅县"],"西藏_拉萨":["城关区","当雄县","堆龙德庆县","曲水县","尼木县","墨竹工卡县","达孜县","林周县"],"西藏_昌都市":["卡若区","贡觉县","类乌齐县","丁青县","察雅县","八宿县","左贡县","芒康县","洛隆县","边坝县","江达县"],"西藏_山南":["乃东区","扎囊县","贡嘎县","桑日县","琼结县","曲松县","措美县","洛扎县","加查县","隆子县","错那县","浪卡子县"],"西藏_日喀则市":["桑珠牧区","江孜县","白朗县","拉孜县","萨迦县","岗巴县","定结县","定日县","聂拉木县","康马县","亚东县","仁布县","南木林县","谢通门县","吉隆县","昂仁县","萨嘎县","仲巴县","樟木口岸"],"西藏_那曲":["那曲县","嘉黎县","比如县","聂荣县","安多县","申扎县","索县","巴青县","班戈县","尼玛县"],"西藏_阿里":["普兰县","札达县","日土县","革吉县","措勤县","噶尔县","改则县"],"西藏_林芝":["巴宜区","工布江达县","米林县","墨脱县","波密县","察隅县","朗县"],"云南_昆明市":["呈贡区","盘龙区","五华区","官渡区","西山区","东川区","安宁市","晋宁县","富民县","宜良县","嵩明县","石林彝族自治县","禄劝彝族苗族自治县","寻甸回族彝族自治县"],"云南_曲靖市":["麒麟区","宣威市","马龙县","沾益县","富源县","罗平县","师宗县","陆良县","会泽县"],"云南_玉溪市":["红塔区","江川区","澄江县","通海县","华宁县","易门县","峨山彝族自治县","新平彝族傣族自治县","元江哈尼族彝族傣族自治县"],"云南_昭通市":["昭阳区","鲁甸县","巧家县","盐津县","大关县","永善县","绥江县","镇雄县","彝良县","威信县","水富县"],"云南_保山市":["隆阳区","腾冲市","施甸县","龙陵县","昌宁县"],"云南_丽江市":["古城区","永胜县","华坪县","玉龙纳西族自治县","宁蒗彝族自治县"],"云南_普洱市":["思茅区","宁洱哈尼族彝族自治县","墨江哈尼族自治县","景东彝族自治县","景谷傣族彝族自治县","镇沅彝族哈尼族拉祜族自治县","江城哈尼族彝族自治县","孟连傣族拉祜族佤族自治县","澜沧拉祜族自治县","西盟佤族自治县"],"云南_临沧市":["临翔区","凤庆县","云县","永德县","镇康县","双江拉祜族佤族布朗族傣族自治县","耿马傣族佤族自治县","沧源佤族自治县"],"云南_德宏傣族景颇族自治州":["芒市","瑞丽市","梁河县","盈江县","陇川县"],"云南_怒江傈僳族自治州":["泸水县","福贡县","贡山独龙族怒族自治县","兰坪白族普米族自治县"],"云南_迪庆藏族自治州":["香格里拉市","德钦县","维西傈僳族自治县"],"云南_大理白族自治州":["大理市","祥云县","宾川县","弥渡县","永平县","云龙县","洱源县","剑川县","鹤庆县","漾濞彝族自治县","南涧彝族自治县","巍山彝族回族自治县"],"云南_楚雄彝族自治州":["楚雄市","双柏县","牟定县","南华县","姚安县","大姚县","永仁县","元谋县","武定县","禄丰县"],"云南_红河哈尼族彝族自治州":["蒙自市","个旧市","开远市","弥勒市","建水县","石屏县","泸西县","绿春县","元阳县","红河县","金平苗族瑶族傣族自治县","河口瑶族自治县","屏边苗族自治县"],"云南_文山壮族苗族自治州":["文山市","砚山县","西畴县","麻栗坡县","马关县","丘北县","广南县","富宁县"],"云南_西双版纳傣族自治州":["景洪市","勐海县","勐腊县"],"重庆_重庆":["九龙坡区","渝中区","江北区","大渡口区","南岸区","沙坪坝区","北碚区","渝北区","巴南区","长寿区","綦江区","永川区","合川区","江津区","潼南区","铜梁区","大足区","荣昌区","璧山县","万州区","梁平县","城口县","巫山县","巫溪县","忠县","开县","奉节县","云阳县","涪陵区","南川区","垫江县","丰都县","武隆县","石柱县","黔江区","秀山县","酉阳县","彭水县"],"青海_西宁市":["城中区","城东区","城西区","城北区","湟源县","湟中县","大通回族土族自治县"],"青海_海东市":["乐都区","平安区","民和回族土族自治县","互助土族自治区","化隆回族自治县","循化撒拉族自治县"],"青海_海北藏族自治州":["海晏县","祁连县","刚察县","门源回族自治县"],"青海_黄南藏族自治州":["同仁县","尖扎县","泽库县","河南蒙古族自治县"],"青海_海南藏族自治州":["共和县","同德县","贵德县","兴海县","贵南县"],"青海_果洛藏族自治州":["玛沁县","班玛县","甘德县","达日县","久治县","玛多县"],"青海_玉树藏族自治州":["玉树市","杂多县","称多县","治多县","囊谦县","曲麻莱县"],"青海_海西蒙古族藏族自治州":["德令哈市","格尔木市","乌兰县","都兰县","天峻县","冷湖行政区","大柴旦行政区","茫崖行政区"],"新疆_乌鲁木齐":["天山区","沙依巴克区","新市区","水磨沟区","头屯河区","达坂城区","米东区","乌鲁木齐县"],"新疆_克拉玛依":["克拉玛依区","独山子区","白碱滩区","乌尔禾区"],"新疆_吐鲁番市":["高昌区","鄯善县","托克逊县"],"新疆_哈密市":["伊州区","伊吾县","巴里坤哈萨克自治县"],"新疆_阿克苏地区":["库车县","新和县","沙雅县","拜城县","温宿县","阿瓦提县","乌什县","柯坪县","阿克苏"],"新疆_喀什地区":["喀什市","疏附县","疏勒县","英吉沙县","泽普县","莎车县","叶城县","麦盖提县","岳普湖县","伽师县","巴楚县","塔什库尔干塔吉克自治县"],"新疆_和田地区":["和田市","和田县","墨玉县","皮山县","洛浦县","策勒县","于田县","民丰县"],"新疆_昌吉回族自治州":["昌吉市","阜康市","呼图壁县","玛纳斯县","奇台县","吉木萨尔县","木垒哈萨克自治县"],"新疆_博尔塔拉蒙古自治州":["博乐市","阿拉山口市","精河县","温泉县"],"新疆_巴音郭楞蒙古自治州":["库尔勒市","轮台县","尉犁县","若羌县","且末县","和静县","博湖县","和硕县","焉耆回族自治县"],"新疆_克孜勒苏柯尔克孜自治州":["阿图什市","阿克陶县","阿合奇县","乌恰县"],"新疆_伊犁哈萨克自治州":["伊宁市","奎屯市","霍尔果斯市","伊宁县","霍城县","巩留县","新源县","昭苏县","特克斯县","尼勒克县","察布查尔锡伯自治县","塔城市","乌苏市","额敏县","沙湾县","托里县","裕民县","和布克赛尔蒙古自治县","阿勒泰市","布尔津县","富蕴县","福海县","哈巴河县","清河县","吉木乃县"],"新疆_石河子":["石河子"],"新疆_阿拉尔市":["阿拉尔市"],"新疆_图木舒克":["图木舒克"],"新疆_五家渠":["五家渠"],"新疆_北屯":["北屯"],"新疆_铁门关":["铁门关"],"新疆_双河":["双河"],"新疆_可克达拉":["可克达拉"],"新疆_昆玉":["昆玉"],"河南_郑州市":["中原区","二七区","管城回族区","金水区","上街区","惠济区","郑东新区","邙山区","登封市","新密市","巩义市","荥阳市","新郑市","中牟县"],"河南_开封市":["金明区","顺河回族区","龙亭区","鼓楼区","禹王台区","杞县","兰考县","通许县","尉氏县","开封县"],"河南_洛阳市":["涧西区","西工区","老城区","瀍河回族区","吉利区","洛龙区","偃师市","孟津县","新安县","栾川县","嵩县","汝阳县","宜阳县","洛宁县","伊川县"],"河南_平顶山市":["新华区","卫东区","湛河区","石龙区","舞钢市","汝州市","宝丰县","叶县","鲁山县","郏县"],"河南_安阳市":["龙安区","北关区","文峰区","开发区","林州市","内黄县","安阳县","滑县","汤阴县"],"河南_鹤壁市":["鹤山区","山城区","淇滨区","浚县","淇县"],"河南_新乡市":["卫滨区","红旗区","凤泉区","牧野区","卫辉市","辉县市","新乡县","获嘉县","原阳县","延津县","封丘县","长垣县"],"河南_焦作市":["解放区","中站区","马村区","山阳区","沁阳市","孟州市","修武县","博爱县","武涉县","温县"],"河南_濮阳市":["高新区","华龙区","濮阳县","清丰县","范县","台前县","南乐县"],"河南_许昌市":["禹州市","长葛市","魏都区","许昌县","鄢陵县","襄城县"],"河南_漯河市":["源汇区","郾城区","召陵区","舞阳县","临颍县"],"河南_三门峡市":["滨湖区","灵宝市","义马市","陕县","卢氏县","渑池县"],"河南_商丘市":["睢阳区","梁园区","永城市","柘城县","虞城县","夏邑县","宁陵县","民权县","睢县"],"河南_周口市":["川汇区","项城市","郸城县","淮阳县","太康县","沈丘县","商水县","扶沟县","鹿邑县","西华县"],"河南_驻马店市":["高新区","驿城区","确山县","泌阳县","遂平县","西平县","上蔡县","汝南县","平舆县","新蔡县","正阳县"],"河南_南阳市":["卧龙区","宛城区","邓州市","南召县","方城县","西峡县","镇平县","内乡县","淅川县","社旗县","唐河县","新野县","桐柏县"],"河南_信阳市":["浉河区","平桥区","罗山县","光山县","新县","商城县","固始县","潢川县","淮滨县","息县"],"河南_济源市":["济源市"],"湖南_长沙市":["岳麓区","芙蓉区","天心区","开福区","雨花区","浏阳市","长沙县","望城县","宁乡县"],"湖南_株洲市":["天元区","荷塘区","芦淞区","石峰区","醴陵市","株洲县","炎陵县","茶陵县","攸县"],"湖南_湘潭市":["雨湖区","岳塘区","湘乡市","韶山市","湘潭县"],"湖南_衡阳市":["石鼓区","雁峰区","珠晖区","蒸湘区","南岳区","耒阳市","常宁市","衡阳县","衡东县","衡山县","衡南县","祁东县"],"湖南_邵阳市":["双清区","大祥区","北塔区","武冈市","邵东县","洞口县","新邵县","绥宁县","新宁县","邵阳县","隆回县","城步苗族自治县"],"湖南_岳阳市":["岳阳楼区","君山区","云溪区","临湘市","汨罗市","岳阳县","湘阴县","平江县","华容县"],"湖南_张家界市":["永定区","武陵源区","慈利县","桑植县"],"湖南_益阳市":["赫山区","资阳区","沅江市","桃江县","南县","安化县"],"湖南_常德市":["武陵区","鼎城区","津市","澧县","临澧县","桃源县","汉寿县","安乡县","石门县"],"湖南_娄底市":["娄星区","冷水江市","涟源市","新化县","双峰县"],"湖南_郴州市":["北湖区","苏仙区","资兴市","宜章县","汝城县","安仁县","嘉禾县","临武县","桂东县","永兴县","桂阳县"],"湖南_永州市":["冷水滩区","芝山区","祁阳县","蓝山县","宁远县","新田县","东安县","江永县","道县","双牌县","江华瑶族自治县"],"湖南_怀化市":["鹤城区","洪江市","会同县","沅陵县","辰溪县","溆浦县","中方县","新晃侗族自治县","芷江侗族自治县","通道侗族自治县","靖州苗族侗族自治县","麻阳苗族自治县"],"湖南_湘西土家族苗族自治州":["吉首市","古丈县","龙山县","永顺县","凤凰县","泸溪县","保靖县","花垣县"],"吉林_长春":["朝阳区","宽城区","二道区","南关区","绿园区","双阳区","九台市","榆树市","德惠市","农安县"],"吉林_吉林市":["船营区","昌邑区","龙潭区","丰满区","舒兰市","桦甸市","蛟河市","磐石市","永吉县"],"吉林_四平市":["铁西区","铁东区","公主岭市","双辽市","梨树县","伊通满族自治县"],"吉林_辽源市":["龙山区","西安区","东辽县","东丰县"],"吉林_通化市":["东昌区","二道江区","梅河口市","集安市","通化县","辉南县","柳河县"],"吉林_白山市":["八道江区","江源县","临江市","靖宇县","抚松县","长白朝鲜族自治县"],"吉林_松原市":["宁江区","乾安县","长岭县","扶余县","前郭尔罗斯蒙古族自治县"],"吉林_白城市":["洮北区","大安市","洮南市","镇赉县","通榆县"],"吉林_延边朝鲜族自治州":["延吉市","图们市","敦化市","龙井市","珲春市","和龙市","安图县","汪清县"],"江西_南昌市":["东湖区","西湖区","青云谱区","湾里区","青山湖区","新建区","南昌县","安义县","进贤县"],"江西_九江市":["浔阳区","庐山区","九江县","共青城市","瑞昌市","永修县","德安县","星子县(庐山市)","都昌县","湖口县","彭泽县","武宁县","修水县"],"江西_上饶市":["信州区","广丰区","上饶县","玉山县","铅山县","横峰县","弋阳县","余干县","鄱阳县","万年县","婺源县","德兴市"],"江西_抚州市":["临川区","南城县","黎川县","南丰县","崇仁县","乐安县","宜黄县","金溪县","资溪县","东乡县","广昌县"],"江西_宜春市":["袁州区","高安市","丰城市","樟树市","奉新县","万载县","上高县","宜丰县","靖安县","铜鼓县"],"江西_吉安市":["吉州区","青原区","吉安县","井冈山市","吉水县","新干县","永丰县","泰和县","遂川县","万安县","安福县","永新县","峡江县"],"江西_赣州市":["章贡区","南康区","信丰县","大余县","赣县","龙南县","定南县","全南县","寻乌县","定远县","瑞金市","宁都县","于都县","会昌县","石城县","上犹县","兴国县","崇义县"],"江西_景德镇市":["昌江区","珠山区","浮梁县","乐平市"],"江西_萍乡市":["安源区","湘东区","莲花县","上栗县","芦溪县"],"江西_新余市":["渝水区","分宜县"],"江西_鹰潭市":["月湖区","余江县","贵溪市"],"辽宁_沈阳":["沈河区","和平区","大东区","皇姑区","铁西区","苏家屯区","东陵区","沈北新区","于洪区","新民市","辽中县","康平县","法库县"],"辽宁_大连":["西岗区","中山区","沙河口区","甘井子区","旅顺口区","瓦房店市","普兰店市","庄河市","长海县","金州区"],"辽宁_鞍山":["铁东区","铁西区","立山区","千山区","海城市","台安县","岫岩满族自治县"],"辽宁_抚顺":["顺城区","新抚区","东洲区","望花区","抚顺县","新宾满族自治县","清原满族自治县"],"辽宁_本溪":["平山区","溪湖区","明山区","南芬区","本溪满族自治县","桓仁满族自治县"],"辽宁_丹东":["振兴区","元宝区","振安区","东港市","凤城市","宽甸满族自治县"],"辽宁_锦州":["凌海市","北宁市","义县","黑山县","古塔区","凌河区","太和区"],"辽宁_营口":["站前区","西市区","鲅鱼圈区","老边区","盖州市","大石桥市"],"辽宁_阜新":["海州区","新邱区","太平区","清河门区","细河区","阜新蒙古族自治县","彰武县"],"辽宁_辽阳":["白塔区","文圣区","宏伟区","弓长岭区","太子河区","灯塔市","辽阳县"],"辽宁_盘锦":["兴隆台区","双台子区","大洼县","盘山县"],"辽宁_铁岭":["银州区","清河区","调兵山市","开原市","铁岭县","西丰县","昌图县"],"辽宁_朝阳":["双塔区","龙城区","北票市","凌源市","朝阳县","建平县","喀喇沁左翼蒙古族自治县"],"辽宁_葫芦岛":["龙港区","连山区","南票区","兴城市","绥中县","建昌县"],"山东_济南市":["市中区","天桥区","历下区","槐荫区","历城区","长清区","章丘区","平阴县","济阳县","南河县"],"山东_青岛市":["市南区","市北区","四方区","李沧区","崂山区","城阳区","黄岛区","胶州市","即墨市","平度市","胶南市","菜西市"],"山东_淄博市":["淄川区","张店区","博山区","临淄区","周村区","桓台县","高青县","沂源县","高新区"],"山东_枣庄市":["市中区","薛城区","峄城区","台儿庄区","山亭区","滕州市"],"山东_东营市":["东营区","河口区","利津县","垦利县","广饶县"],"山东_烟台市":["芝罘区","福山市","牟平区","莱山区","长岛县","龙口市","莱阳市","莱州市","蓬莱市","招远县","栖霞市","海阳市"],"山东_潍坊市":["奎文区","潍城区","寒亭区","坊子区","昌乐县","临朐县","青州市","诸城市","寿光市","安丘县","高密市","昌邑市"],"山东_济宁市":["任城区","曲阜市","兖州区","邹城市","微山县","鱼台县","金乡县","嘉祥县","汶上县","泗水县","梁山县"],"山东_泰安市":["泰山区","岱岳区","新泰市","肥城市","宁阳县","东平县"],"山东_威海":["环翠区","文登市","荣成市","乳山市"],"山东_日照":["东港区","岚山区","五莲县","莒县"],"山东_滨州":["滨城区","惠民县","阳信县","无棣县","沾化区","博兴县","邹平县"],"山东_德州":["德城区","乐陵市","禹城市","陵城区","宁津县","庆云县","临邑县","齐河县","平原县","夏津县","武城县"],"山东_聊城":["东昌府区","临清市","阳谷县","莘县","茌平县","东阿县","冠县","高唐县"],"山东_临沂":["兰山区","罗庄区","河东区","沂南县","郯城县","沂水县","兰陵县","费县","平邑县","莒南县","蒙阴县","临沭县"],"山东_菏泽":["牡丹区","曹县","单县","成武县","巨野县","郓城县","鄄城县","定陶县","东明县"],"山东_莱芜市":["莱城区","钢城区"],"陕西_西安市":["新城区","碑林区","莲湖区","灞桥区","未央区","雁塔区","阎良区","临潼区","长安区","高陵区","蓝田县","周至县","卢县"],"陕西_宝鸡市":["渭滨区","潼南区","金台区","陈仓区","凤翔区","岐山县","扶风县","眉县","陇县","千阳县","麟游县","凤县","太白县"],"陕西_咸阳市":["秦都区","渭城区","兴平市","三原县","泾阳县","武功县","乾县","礼泉县","永寿县","彬县","长武县","旬邑县","淳化县"],"陕西_渭南市":["临渭区","华州区","韩城市","华阴市","蒲城县","富平县","潼关县","大荔县","合阳县","澄城县","白水县"],"陕西_铜川市":["耀州区","王益区","印台区","宜君县"],"陕西_延安市":["宝塔区","延长县","子长县","安塞县","志丹县","吴起县","甘泉县","富县","洛川县","宜川县","黄龙县","黄陵县","延川县"],"陕西_榆林市":["横山区","神木县","府谷县","靖边县","定边县","缓德县","米脂县","佳县","吴堡县","清涧县","子洲县","榆阳区"],"陕西_安康市":["旬阳县","石泉县","平利县","汉阴县","宁陕县","紫阳县","岚皋县","镇坪县","白河县"],"陕西_汉中市":["汉台区","南郑县","城固县","洋县","西乡县","勉县","宁强县","略阳县","镇巴县","留坝县","佛坪县"],"陕西_商洛市":["商州区","洛南县","丹凤县","商南县","山阳县","镇安县","柞水县"],"陕西_杨凌示范区":["杨陵区"],"广西_南宁市":["兴宁区","青秀区","江南区","西乡塘区","良庆区","邕宁区"],"广西_柳州市":["城中区","鱼峰区","柳南区","柳北区"],"广西_桂林市":["秀峰区","叠彩区","象山区","七星区","雁山区"],"广西_梧州市":["万秀区","蝶山区","长洲区","苍梧县","藤县","蒙山县","岑溪市"],"广西_北海市":["海城区","银海区","铁山港区","合浦县"],"广西_南宁地区":["武鸣县","隆安县","马山县","上林县","宾阳县","横县","江州区","扶绥县","宁明县","龙州县","大新县","天等县","凭祥市"],"广西_柳州地区":["柳江县","柳城县","鹿寨县","融安县","融水县","三江县","兴宾区","忻城县","象州县","武宣县","金秀县","合山市"],"广西_桂林地区":["临桂县","阳朔县","灵川县","全州县","兴安县","永福县","灌阳县","龙胜县","资源县","平乐县","荔浦县","恭城县"],"广西_贺州市":["八步区","平桂管理区","昭平县","钟山县","富川县"],"广西_玉林市":["市辖区","玉州区","福绵管理区","容县","陆川县","博白县","兴业县","北流市"],"广西_百色市":["右江区","田阳县","田东县","平果县","德保县","靖西县","那坡县","凌云县","乐业县","田林县","西林县","隆林县"],"广西_河池市":["金城江区","南丹县","天峨县","凤山县","东兰县","罗城县","环江县","巴马县","都安县","大化县","宜州市"],"广西_钦州市":["市辖区","钦南区","钦北区","灵山县","浦北县"],"广西_防城港市":["市辖区","防城区","上思县","东兴市"],"广西_贵港市":["市辖区","港北区","港南区","覃塘区","平南县","桂平市"],"宁夏_银川":["兴庆区","西夏区","金凤区","永宁县","贺兰县","灵武市"],"宁夏_石嘴山":["大武口区","惠农区","平罗县"],"宁夏_吴忠市":["利通区","同心县","盐池县","青铜峡市"],"宁夏_固原市":["原州区","彭阳县","泾源县","隆德县","西吉县"],"宁夏_中卫市":["沙坡头区","海原县","中宁县"],"黑龙江_哈尔滨":["道里区","南岗区","道外区","香坊区","平房区","松北区","呼兰区","阿城区","依兰县","方正县","宾县","巴彦县","木兰县","通河县","延寿县","双城市","尚志市","五常市"],"黑龙江_齐齐哈尔":["龙沙区","建华区","铁锋区","昂昂溪区","富拉尔基区","碾子山区","梅里斯区","龙江县","依安县","泰来县","甘南县","富裕县","克山县","克东县","拜泉县","讷河市"],"黑龙江_牡丹江":["东安区","阳明区","爱民区","西安区","东宁县","林口县","绥芬河市","海林市","宁安市","穆棱市"],"黑龙江_佳木斯":["向阳区","前进区","东风区","郊区","桦南县","桦川县","汤原县","抚远县","同江市","富锦市"],"黑龙江_大庆":["萨尔图区","龙凤区","让胡路区","红岗区","大同区","肇州县","肇源县","林甸县","杜尔伯特"],"黑龙江_伊春":["伊春区","南岔区","友好区","西林区","翠峦区","新青区","美溪区","金山屯区","五营区","乌马河区","汤旺河区","带岭区","乌伊岭区","红星区","上甘岭区","嘉荫县","铁力市"],"黑龙江_鸡西":["鸡冠区","恒山区","滴道区","梨树区","城子河区","麻山区","虎林市","密山市","鸡东县"],"黑龙江_鹤岗":["向阳区","工农区","南山区","兴安区","东山区","兴山区","萝北县","绥滨县"],"黑龙江_双鸭山":["尖山区","岭东区","四方台区","宝山区","集贤县","友谊县","宝清县","饶河县"],"黑龙江_七台河":["新兴区","桃山区","茄子河区","勃利县"],"黑龙江_绥化":["北林区","肇东市","安达市","海伦市","望奎县","兰西县","青冈县","明水县","庆安县","绥棱县"],"黑龙江_黑河":["爱辉区","嫩江县","逊克县","孙吴县","北安市","五大连池市"],"黑龙江_大兴安岭":["加格达奇区","松岭区","新林区","呼中区","呼玛县","塔河县","漠河县"],"内蒙古_呼和浩特":["新城区","回民区","玉泉区","赛罕区","土默特左旗","托克托县","和林格尔县","清水河县","武川县"],"内蒙古_包头市":["东河区","昆都仑区","青山区","石拐区","白云矿区","九原区","土默特右旗","固阳县","达茂旗"],"内蒙古_乌海市":["海勃湾区","海南区","乌达区"],"内蒙古_赤峰市":["红山区","元宝山区","松山区","阿旗","巴林左旗","巴林右旗","林西县","克什克腾旗","翁牛特旗","喀喇沁旗","宁城县","敖汉旗"],"内蒙古_呼伦贝尔市":["海拉尔区","阿荣旗","莫旗","鄂伦春旗","鄂温克旗","陈巴尔虎旗","新左旗","新右旗","满洲里市","牙克石市","扎兰屯市","额尔古纳市","根河市"],"内蒙古_兴安盟":["乌兰浩特市","阿尔山市","科右前旗","科右中旗","扎赉特旗","突泉县"],"内蒙古_通辽市":["科尔沁区","科左中旗","科左后旗","开鲁县","库伦旗","奈曼旗","扎鲁特旗","霍林郭勒市"],"内蒙古_锡林郭勒盟":["锡林浩特市","二连浩特市","阿巴嘎旗","苏尼特左旗","东乌旗","西乌旗","太仆寺旗","镶黄旗","正镶白旗","正蓝旗","多伦县"],"内蒙古_乌兰察布盟":["集宁市","丰镇市","卓资县","化德县","商都县","兴和县","凉城县","察右前旗","察右中旗","察右后旗","四子王旗"],"内蒙古_鄂尔多斯市":["东胜区","达拉特旗","鄂托克前旗","鄂托克旗","杭锦旗","乌审旗","伊金霍洛旗"],"内蒙古_巴彦淖尔盟":["临河市","五原县","磴口县","乌拉特前旗","乌拉特中旗","乌拉特后旗","杭锦后旗"],"内蒙古_阿拉善盟":["阿拉善左旗","阿拉善右旗"],"海南_海口":["龙华区","秀英区","琼山区","美兰区"],"海南_三亚市":[""],"海南_琼北地区":["文昌市","琼海市","万宁市","儋州市","临高县","澄迈县","定安县","屯昌县"],"海南_琼南地区":["东方市","五指山市","昌江县","白沙县","陵水县","乐东县","保亭县","琼中县"],"海南_洋浦开发区":[""],"海南_三沙市":[""],"湖北_武汉":["武昌区","江岸区","硚口区","汉阳区","江汉区","青山区","洪山区","东西湖区","汉南区","蔡甸区","武汉开发区","东湖开发区","东湖风景区","江夏区","黄陂区","新洲区","武汉化工区"],"湖北_黄石":["黄石港区","下陆区","西塞山区","开发区","铁山区","大冶市","阳新市"],"湖北_十堰市":["张湾区","茅箭区","郧县","郧西县","竹溪县","竹山县","房县","丹江口市"],"湖北_荆州市":["荆州区","沙市区","开发区","江陵县","松滋市","公安县","石首市","监利县","洪湖市"],"湖北_宜昌市":["西陵区","伍家岗区","点军区","猇亭区","夷陵区","远安县","兴山县","秭归县","长阳县","五峰县","宜都市","当阳市","枝江市"],"湖北_襄阳市":["襄城区","樊城区","襄州区","高新区","鱼梁洲开发区","南漳县","谷城县","保康县","老河口市","枣阳市","宜城市"],"湖北_鄂州市":["鄂城区","华容区","梁子湖区","葛店开发区","花湖开发区","鄂州开发区"],"湖北_荆门市":["东宝区","掇刀区","京山县","沙洋县","钟祥市"],"湖北_黄冈市":["黄州区","团风县","红安县","麻城市","罗田县","英山县","浠水县","蕲春县","武穴市","黄梅县"],"湖北_孝感市":["孝南区","应城市","云梦县","安陆市","汉川市","孝昌县","大悟县"],"湖北_咸宁市":["咸安区","通山县","嘉鱼县","通城县","崇阳县","赤壁市"],"湖北_仙桃市":["仙桃市"],"湖北_潜江市":["潜江市"],"湖北_神农架":["神农架"],"湖北_恩施州":["恩施市","利川市","建始县","巴东县","宣恩县","咸丰县","来凤县","鹤峰县"],"湖北_天门市":["天门市"],"湖北_随州市":["曾都区","广水市","随县"],"广东_广州市":["越秀区","荔湾区","海珠区","天河区","白云区","黄埔区","番禺区","花都区","南沙区","萝岗区","增城市","从化市"],"广东_深圳市":["福田区","罗湖区","南山区","盐田区","宝安区","龙岗区"],"广东_珠海市":["香洲区","三灶区","横琴区","淇澳区","万山区","斗门县"],"广东_汕头市":["金平区","龙湖区","澄海区","濠江区","潮阳区","潮南区","南澳县"],"广东_佛山市":["南海区","三水区","高明区","禅城区"],"广东_韶关市":["浈江区","武江区","曲江区","乐昌市","南雄市","始兴县","仁化县","翁源县","新丰县","乳源县"],"广东_湛江市":["赤坎区","霞山区","坡头区","麻章区","开发区","吴川市","雷州市","廉江市","遂溪县","徐闻县"],"广东_肇庆":["端州区","鼎湖区","广宁县","怀集县","封开县","德庆县","高要市","四会市"],"广东_江门":["蓬江区","江海区","新会区","台山市","开平市","鹤山市","恩平市"],"广东_茂名":["茂南区","茂港区","电白县","高州市","化州市","信宜市"],"广东_惠州":["惠城区","博罗县","惠东县","龙门县","惠阳市","大亚湾区"],"广东_梅州":["梅江区","兴宁市","梅县","平远县","蕉岭县","大埔县","丰顺县","五华县"],"广东_汕尾":["市城区","海丰县","陆河县","陆丰市"],"广东_河源 ":["源城区","紫金县","龙川县","连平县","和平县","东源县"],"广东_江阳":["江城区","海陵区","高新区","阳东县","阳西县","阳春市"],"广东_清远":["清城区","英德市","佛冈县","连州市","阳山县","清新县","连南县","连山县"],"广东_东莞市":["东莞市"],"广东_中山市":["中山市"],"广东_潮州市":["湘桥区","枫溪区","潮安县","饶平县"],"广东_揭阳市":["榕城区","揭东区","揭西县","惠来县","普宁市"],"广东_云浮市":["云城区","新兴县","郁南县","云安县","罗定市","顺德区"]}';
        $city=json_decode($city,true);
        return sendJson(1,'',0,['city'=>$city[$data['city']]]);
    }
    public function getCommunity(Request $request){
        //获取数据
        $data=$request->param();
        $ids=Db::table('szwl_communityID')
            ->field('community_id')
            ->where('province',$data['province'])
            ->where('city',$data['city'])
            ->where('area',$data['area'])
            ->where('member_id',0)
            ->select();

        return sendJson(1,'',0,['city'=>$ids]);
    }  
}
