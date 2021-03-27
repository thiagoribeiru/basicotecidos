var ind = "";
var linhaAtv = 0; //zerar ao recarregar pagina
var contLinhas = 0; //zerar ao recarregar pagina
function redireciona() {
    // alert(ind);
    window.location.href = ind;
}
function abrirPopUp(url,w,h,fechamento) {
    // var newW = w + 100;
    var newW = w;
    // var newH = h + 100;
    // var newH = h;
    var newH = h + 50;
	var left = (screen.width-newW)/2;
	var top = (screen.height-newH)/2;
	var newwindow = window.open(url, '', 'width='+newW+',height='+newH+',left='+left+',top='+top);
	if (typeof(fechamento) != "undefined") {window.close();}
	newwindow.resizeTo(newW, newH);
	 
	//posiciona o popup no centro da tela
	newwindow.moveTo(left, top);
	newwindow.focus();
	return false;
}
function abrirPopUpFull(url,fechamento) {
	
	var newwindow = window.open(url, '','status=no, toolbar=no, menubar=no, location=no, fullscreen=1, scrolling=auto');
	if (typeof(fechamento) != "undefined") {window.close();}
	
	return false;
}
function carregaHtml(pagOrigem,elementoDestino,opc_func_success) {
    var origem = pagOrigem;
    var destino = elementoDestino;
    if (typeof(opc_func_success)!="undefined") {
        var funcao = opc_func_success;
    }
    $.ajax({
        method: "GET",
        dataType: "html",
        url: origem,
        beforeSend: function(){
            $(destino).html("<img src=\"../images/249.png\" class=\"img_load\">");
        },
        success: function(data){
            var html = data;
            $(destino).html(html);
            if (typeof(funcao)!="undefined" && typeof(funcao)=="function") {
                funcao.call();
            }
        }
    });
}
function funcoes_box_proc_cli() {
    $("#box_proc_cli #filtro").ready(function(){
        $("#box_proc_cli #filtro").keyup(function(){
            var nomeFiltro = $(this).val().toLowerCase();
            $('#box_body table tbody').find('tr').each(function() {
                var conteudoCelula = $(this).find('td').text();
                var corresponde = conteudoCelula.toLowerCase().indexOf(nomeFiltro) >= 0;
                $(this).css('display', corresponde ? '' : 'none');
            });
        });
        $("#box_proc_cli tr").click(function(){
            var atId = $(this).attr('atId');
            var atNome = $(this).attr('atNome');
            var atEmail = $(this).attr('atEmail');
            // $("#box_proc_cli").hide();
            $("#box_proc_cli").fadeOut(400);
            $("#adc_ped_body .cod_cli").val(atId);
            $("#adc_ped_body .nome_cli").val(atNome);
            $("#adc_ped_body .login_cli").val(atEmail);
        });
    });
}
function exibeSubsub() {
    if ($("#box_edit_item #tab3").val()!="") {
        if ($("#box_edit_item #tab4").val()!="") {
            var quant = parseFloat($("#box_edit_item #tab3").val().replace(",","."));
            var valor = parseFloat($("#box_edit_item #tab4").val().replace(",","."));
            if ($("#box_edit_item #tab5").val()=="") var desc = 0;
            else var desc = parseFloat($("#box_edit_item #tab5").val().replace(",","."));
            if ($("#box_edit_item #tab6").val()=="") var ipi = 0;
            else var ipi = parseFloat($("#box_edit_item #tab6").val().replace(",","."));
            // var subsub = (quant*valor)*(1+((ipi-desc)/100));
            var subsub = subTotal(quant,valor,desc,ipi);
            $("#box_edit_item #subsub").text("Sub total: R$ "+subsub.toFixed(2));
        }
    }
}
//-----------------------------------------------------------------
// Entrada DD/MM/AAAA
//-----------------------------------------------------------------
function fctValidaData(obj) {
    var data = obj.value;
    var dia = data.substring(0,2)
    var mes = data.substring(3,5)
    var ano = data.substring(6,10)
 
    //Criando um objeto Date usando os valores ano, mes e dia.
    var novaData = new Date(ano,(mes-1),dia);
 
    var mesmoDia = parseInt(dia,10) == parseInt(novaData.getDate());
    var mesmoMes = parseInt(mes,10) == parseInt(novaData.getMonth())+1;
    var mesmoAno = parseInt(ano) == parseInt(novaData.getFullYear());
 
    if (!((mesmoDia) && (mesmoMes) && (mesmoAno)))
    {
        // alert('Data informada é inválida!');   
        // obj.focus();    
        return false;
    }  
    return true;
}
function linWhite(refTabela) {
    // Captura a referência da tabela com id “minhaTabela”
    var table = document.getElementById(refTabela);
    // Captura a quantidade de linhas já existentes na tabela
    var numOfRows = table.rows.length;
    for (var i=1;i<=numOfRows;i++) {
        // document.getElementById("lin"+i).setAttribute("bgcolor","");
        table.rows[i-1].setAttribute("bgcolor","");
    }
}
function selecionar(linId) {
    linhaAtv = linId;
}
function desselecionar() {
    linhaAtv = 0;
}
function excluiLin(refTab,refLin) {
    if (typeof(linhaAtv)=='string'||typeof(linhaAtv)=='number') {
        var linha = $("#"+refTab+" #lin"+refLin);
        linha.remove();
        linhaAtv = 0;
    }
}
function excluiLinObj() {
    if (typeof(linhaAtv)=='object') {
        linhaAtv.remove();
        linhaAtv = 0;
    }
}
function minimizarPront() {
    $("#debug").show();
    $("#debug .pront").hide();
    $("#debug").width(300);
    $("#debug .minimizar").hide();
    $("#debug .maximizar").show();
}
function maximizarPront() {
    $("#debug").show();
    $("#debug .pront").show();
    $("#debug").width(500);
    $("#debug .minimizar").show();
    $("#debug .maximizar").hide();
}
function escrevePront(contHtml,nomeRetorno) {
    if (nomeRetorno!=undefined) var remetente = nomeRetorno+": ";
    else var remetente = "";
    var anterior = $("#debug .pront").html();
    if (anterior!="")
        $("#debug .pront").html(anterior+"<br>-> "+remetente+contHtml);
    else
        $("#debug .pront").html("-> "+remetente+contHtml);
    maximizarPront();
    $("#debug .pront").scrollTop($("#debug .pront")[0].scrollHeight);
}
function gravarPedido() {
    var tabela = document.getElementById("tabela_itens");
    var numLinhas = tabela.rows.length;
    var clienteCod = $("#adc_ped_body .cod_cli").val();
    if (numLinhas>0&&(clienteCod!=""&&clienteCod!=undefined)) {
        var linha = tabela.getElementsByTagName("tr");
        var vetLinha = new Array();
        var vetCelula = 0;
        for (j=0;j<numLinhas;j++) {
            var celula = linha[j].getElementsByTagName("td");
            vetCelula = new Array();
            for (i=0;i<10;i++) {
                vetCelula[i] = celula[i].innerHTML;
            }
            vetCelula[10] = clienteCod;
            vetLinha[j] = vetCelula;
        }
        var serial = JSON.stringify(vetLinha);
        $.ajax({
            method: "GET",
            dataType: "json",
            url: "gravaPed.php",
            data : "serial="+serial+"&cliente="+clienteCod,
            beforeSend: function(){
                // escrevePront("processando...");
            },
            success: function(data){
                if (data['html'] != "") {
                    var html = data['html'];
                    escrevePront(html,"gravaPed.php");
                } 
                if (data['success']!="1"||data['success']==undefined) {
                    escrevePront("Erro no cadastro, favor verificar!","Ajax");
                } else {
                    // escrevePront("Cadastrado!","Ajax");
                    document.location.reload();
                }
            },
            error: function(data){
                escrevePront("Erro no cadastro, favor verificar!","Ajax");
            }
        });
    }
}
function removePed() {
    if (typeof(linhaAtv)=='object') {
        var codPed = linhaAtv.attr("ped");
        $.ajax({
            method: "GET",
            dataType: "json",
            url: "excluiPed.php",
            data : "cod_ped="+codPed,
            beforeSend: function(){
                // escrevePront("processando...");
            },
            success: function(data){
                if (data['html'] != "") {
                    var html = data['html'];
                    escrevePront(html,"AjaxHTML");
                } 
                if (data['success']!="1"||data['success']==undefined) {
                    escrevePront(html,"AjaxHTML");
                } else {
                    linhaAtv.remove();
                    linhaAtv = 0;
                }
            },
            error: function(data){
                escrevePront("Erro no cadastro, favor verificar!","Ajax");
            }
        });
    }
}
function AdicionarFiltro(tabela, coluna) {
    var cols = $("#" + tabela + " thead tr:first-child th").length;
    if ($("#" + tabela + " thead tr").length == 1) {
        var linhaFiltro = "<tr>";
        for (var i = 0; i < cols; i++) {
            linhaFiltro += "<td></td>";
        }
        linhaFiltro += "</tr>";
 
        $("#" + tabela + " thead").append(linhaFiltro);
    }
 
    var colFiltrar = $("#" + tabela + " thead tr:nth-child(2) td:nth-child(" + coluna + ")");
 
    $(colFiltrar).html("<select id='filtroColuna_" + coluna.toString() + "'  class='filtroColuna'> </select>");
 
    var valores = new Array();
 
    $("#" + tabela + " tbody tr").each(function () {
        var txt = $(this).children("td:nth-child(" + coluna + ")").text();
        if (valores.indexOf(txt) < 0) {
            valores.push(txt);
        }
    });
    $("#filtroColuna_" + coluna.toString()).append("<option>TODOS</option>")
    for (elemento in valores) {
        $("#filtroColuna_" + coluna.toString()).append("<option>" + valores[elemento] + "</option>");
    }
    // alert(coluna.toString());
    $("#filtroColuna_" + coluna.toString()).change(function () {
        var filtro = $(this).val();
        $("#" + tabela + " tbody tr").show();
        if (filtro != "TODOS") {
            $("#" + tabela + " tbody tr").each(function () {
                var txt = $(this).children("td:nth-child(" + coluna + ")").text();
                if (txt != filtro) {
                    $(this).hide();
                }
            });
        }
    });
 
};
function limpar_jan(refDiv,subdivs,hiddens) {
    var div = document.getElementById(refDiv).firstChild;
    for(div; div != null; div = div.nextSibling){
        if(document.getElementById(div.id)){
            var objeto = document.getElementById(div.id);
            if(objeto == "[object HTMLDivElement]"){
                if(subdivs){
                    limpar_jan(objeto.id, subdivs, hiddens);
                }
            }
            else if((objeto.type == 'text')||(objeto.type == 'password')||(objeto.type == 'textarea')){
                if (objeto.readOnly == 'true') {
                    objeto.readOnly = false;
                    objeto.value = '';
                    objeto.readOnly = true;
                } else {
                    objeto.value = '';
                }
            } else if(objeto.type == 'select-one'){
                objeto.selectedIndex = -1;
            } else if(objeto.type == 'checkbox'){
                objeto.checked = false;
            } else if(objeto.type == 'hidden' && hiddens == true) {
                escrevePront(objeto.value);
                objeto.value = '';
                escrevePront(objeto.value);
            }
        }
    }
}
function limpa_ped() {
    limpar_jan("tela_adc_ped",true,true);
    var refTabela = $("#tela_adc_ped .container_tabela_itens_body table").attr("id");
    linWhite(refTabela);
    desselecionar();
    var table = document.getElementById(refTabela);
    var numOfRows = table.rows.length;
    for (var i=numOfRows-1;i>=0;i--) {
        table.rows[i].remove();
    }
}
function float2moeda(num) {
   x = 0;
   if(num<0) {
      num = Math.abs(num);
      x = 1;
   }
   if(isNaN(num)) num = "0";
      cents = Math.floor((num*100+0.5)%100);
   num = Math.floor((num*100+0.5)/100).toString();
   if(cents < 10) cents = "0" + cents;
      for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
         num = num.substring(0,num.length-(4*i+3))+'.'
               +num.substring(num.length-(4*i+3));
   ret = num + ',' + cents;
   if (x == 1) ret = ' - ' + ret;return ret;
}
function pegaCodPed() {
    return document.getElementById("cod_ped").value;
}
function chamaLightbox(checkoutcode,ped_cod) {
    isOpenLightbox = PagSeguroLightbox({
        code: "'"+checkoutcode+"'"},
        {
        success : function(transactionCode) {
            // alert("success - " + transactionCode);
            $.ajax({
                method: "GET",
                // dataType : "json",
                url: "finalizaPedPosPag.php",
                data: "cod_ped="+ped_cod+"&codepagseg="+transactionCode,
                success: function() {
                    window.location.reload();
                    // $("#carregando").hide();
                }
            });
        },
        abort : function() {
            // alert("abort");
            $("#carregando").hide();
        }
    });
    if (!isOpenLightbox){
        location.href="https://pagseguro.uol.com.br/v2/checkout/payment.html?code="+checkoutcode;
    }
}
function finalizaPedido() {
    var ativo = document.getElementById("finalizar").getAttribute("ativo");
    if (ativo=='true') {
        $("#carregando").show();
        var ped_cod = pegaCodPed();
        $.ajax({
            method: "GET",
            dataType : "json",
            url: "req_pagsegHTML.php",
            data: "cod_ped="+ped_cod,
            success: function(json) {
                if (json.autoriza=='1') {
                    chamaLightbox(json.code,ped_cod);
                } else if (json-autoriza=='0') {
                    alert(json.mensagem);
                    $("#carregando").hide();
                }
            },
            error: function() {
                alert("Erro na requisição de pagamento. Favor contactar o administrador do sistema!");
                $("#carregando").hide();
            }
        });
    } else alert("Pedido já foi finalizado.");
}
function subTotal (quant,valor,desc,ipi) {
    var subtotal = ((quant*valor)-((quant*valor)*(desc/100)))+(((quant*valor)-((quant*valor)*(desc/100)))*(ipi/100));
    // var tab9 = (tab3*tab4)*(1+((tab6-tab5)/100));
    return subtotal;
}
// function atribuirId(id) {
//     var link = document.getElementById("permicoes");
//     link.attr("usr_id",id);
// }
function linkPermicoes(link) {
    // $("#janela_edit_user #permicoes").attr("usr_id",id);
    // atribuirId(id);
    // var link = document.getElementById("permicoes");
    var user = link.getAttribute("usr_id");
    abrirPopUp('permis.php?user='+user,400,600);
    // alert("teste");
    return false;
}

$(document).ready(function(){
    $.ajax({
        dataType : "html",
        url: "prontCmd.php",
        success : function(data){
            var html = data;
            var pagina = $("body").html();
            $("body").html(pagina+"<br>"+html);
            docReady();
        },
        error : function(){
            docReady();
        }
    });
});
function docReady() {
    $("#tabela_pedidos").ready(function(){
        for (var co=1;co<=6;co++) {
            AdicionarFiltro('tabela_pedidos',co);
        }
        // $("#tabela_pedidos").tablesorter();
    });
    $("#tabela_pedidos_cli").ready(function(){
        for (var co=1;co<=6;co++) {
            AdicionarFiltro('tabela_pedidos_cli',co);
        }
        // $("#tabela_pedidos_cli").tablesorter();
    });
    $("#tabela_pedidos_cli tbody tr").click(function(){
        var linha = $(this);
        var codPedido = linha.attr("ped");
        $("#fundo_fumace").fadeIn(400);
        $.ajax({
            url: "consulta_ped.php",
            method: "GET",
            dataType: "json",
            data: "cod="+codPedido,
            beforeSend: function(){
                
            },
            success: function(json){
                if(json.error=="0") {
                    $("#adc_ped_body #cod_ped").val(json.cod);
                    var tab = json.tabela;
                    var total = 0;
                    for (j=0;j<tab.length;j++) {
                        var lin = tab[j];
                        // Captura a referência da tabela com id “minhaTabela”
                        var table = document.getElementById("tabela_itens");
                        // Captura a quantidade de linhas já existentes na tabela
                        var numOfRows = table.rows.length;
                        if (numOfRows>0) {
                            // Captura a quantidade de colunas da última linha
                            var numOfCols = table.rows[numOfRows-1].cells.length;
                            // Insere uma linha no fim da tabela.
                            var newRow = table.insertRow(numOfRows);
                            // Faz um loop para criar as colunas
                            for (var i=0;i<numOfCols;i++) {
                                // Insere uma coluna na nova linha 
                                newCell = newRow.insertCell(i);
                                //largura celulas
                                if (i==0) newCell.width = "62px";
                                if (i==1) newCell.width = "250px";
                                if (i==2) newCell.width = "30px";
                                if (i==3) newCell.width = "70px";
                                if (i==4) newCell.width = "60px";
                                if (i==5) newCell.width = "45px";
                                if (i==6) newCell.width = "45px";
                                if (i==7) newCell.width = "80px";
                                if (i==8) newCell.width = "80px";
                                if (i==9) newCell.width = "100px";
                                //classes
                                if (i==0) newCell.className = "alfa";
                                if (i==2) newCell.className = "al_ce";
                                if (i==3||i==4||i==5||i==6||i==7||i==8) newCell.className = "al_ri";
                                if (i==9) newCell.className = "omega";
                                //valores
                                if (i==0) newCell.innerHTML = lin[0];
                                if (i==1) newCell.innerHTML = lin[1];
                                if (i==2) newCell.innerHTML = lin[2];
                                if (i==3) newCell.innerHTML = float2moeda(lin[3]);
                                if (i==4) newCell.innerHTML = "R$ "+float2moeda(lin[4]);
                                if (i==5) newCell.innerHTML = float2moeda(lin[5])+"%";
                                if (i==6) newCell.innerHTML = float2moeda(lin[6])+"%";
                                if (i==7) newCell.innerHTML = lin[7];
                                if (i==8) newCell.innerHTML = lin[8];
                                if (i==9) newCell.innerHTML = "R$ "+float2moeda(lin[9]);
                            }
                        } else {
                            var newRow = table.insertRow(0);
                            for (var i=0;i<10;i++) {
                                newCell = newRow.insertCell(i);
                                //largura celulas
                                if (i==0) newCell.width = "62px";
                                if (i==1) newCell.width = "250px";
                                if (i==2) newCell.width = "30px";
                                if (i==3) newCell.width = "70px";
                                if (i==4) newCell.width = "60px";
                                if (i==5) newCell.width = "45px";
                                if (i==6) newCell.width = "45px";
                                if (i==7) newCell.width = "80px";
                                if (i==8) newCell.width = "80px";
                                if (i==9) newCell.width = "100px";
                                //classes
                                if (i==0) newCell.className = "alfa";
                                if (i==2) newCell.className = "al_ce";
                                if (i==3||i==4||i==5||i==6||i==7||i==8) newCell.className = "al_ri";
                                if (i==9) newCell.className = "omega";
                                //valores
                                if (i==0) newCell.innerHTML = lin[0];
                                if (i==1) newCell.innerHTML = lin[1];
                                if (i==2) newCell.innerHTML = lin[2];
                                if (i==3) newCell.innerHTML = float2moeda(lin[3]);
                                if (i==4) newCell.innerHTML = "R$ "+float2moeda(lin[4]);
                                if (i==5) newCell.innerHTML = float2moeda(lin[5])+"%";
                                if (i==6) newCell.innerHTML = float2moeda(lin[6])+"%";
                                if (i==7) newCell.innerHTML = lin[7];
                                if (i==8) newCell.innerHTML = lin[8];
                                if (i==9) newCell.innerHTML = "R$ "+float2moeda(lin[9]);
                            }
                        }
                        total = Number(total)+Number(lin[9]);
                    }
                    $("#adc_ped_body #subtotal").text("R$ "+float2moeda(total));
                    if (json.finalizado=='1') {
                        $("#adc_ped_body #finalizar").attr('ativo',false);
                        $("#adc_ped_body #finalizar").attr('class','botaofalse');
                    } else if (json.finalizado=='0') {
                        $("#adc_ped_body #finalizar").attr('ativo',true);
                        $("#adc_ped_body #finalizar").attr('class','botao');
                    }
                } else {
                    alert(json.mensagem);
                }
            },
            error: function(){
                alert("Error: requestUrl");
            }
        });
    });
    $("#debug .exit .sair").click(function(){
        minimizarPront();
        $("#debug").hide();
    });
    $("#debug .exit .minimizar").click(function(){
        minimizarPront();
    });
    $("#debug .exit .maximizar").click(function(){
        maximizarPront();
    });
    $('#form_login').submit(function(){
        //verifica se os dois campos estão preenchidos
        if ($('#email').val()!="" && $('#senha').val()!="") {
            var valores = $('#form_login').serialize();
            var url_form = $('#form_login').attr('action');
            $.ajax({
                method: "GET",
                dataType : "json",
                url: url_form,
                // data: valores+"&rel=faturamento",
                data: valores,
                beforeSend: function(){
                    $('#carregando').show();
                },
                success: function(json){
                    $('#carregando').hide();
                    if (json.autoriza=='1') {
                        $('.mensagem span').html(json.mensagem+"<br><img src='images/249.png' width='20px'>");
                        $('.mensagem').fadeIn(200).delay(3000).fadeOut(200);
                        ind = json.index;
                        var timeoutID = window.setTimeout(redireciona,2000);
                        $('#form_login').fadeOut("slow");
                    } else {
                        $('.mensagem span').html(json.mensagem);
                        $('.mensagem').fadeIn(200).delay(3000).fadeOut(200);
                    }
                }
            });
        } else {
            alert('Preencha todos os campos!');
        }
        return false;
    });
    //editar usuarios com duplo clique
    // $("#form_users tbody td").dblclick(function () {
    //     if ($(this).attr("d_click")!='no') {
    //         var conteudoOriginal = $(this).text();
    //         var name = $(this).attr('name');
    //         var idd = $(this).attr('idd');
            
    //         // $(this).addClass("celulaEmEdicao");
    //         $(this).html(
    //             "<input type='text' name='" + name + "' value='" + conteudoOriginal + "' />"+
    //             "<input type='hidden' name='idd' value='"+idd+"'>"
    //             );
    //         $(this).children().first().focus();
    
    //         $(this).children().first().keypress(function (e) {
    //             if (e.which == 13) {
    //                 var novoConteudo = $(this).val();
    //                 $(this).parent().text(novoConteudo);
    //                 // $(this).parent().removeClass("celulaEmEdicao");
    //             }
    //         });
    		
    //     	$(this).children().first().blur(function(){
    //     		$(this).parent().text(conteudoOriginal);
    //     // 		$(this).parent().removeClass("celulaEmEdicao");
    //     	});
    //     }
    // });
    $('#form_users #titulo .caixa_corte img').click(function(){
        abrirPopUp('addUsers.php',400,210);
    });
    $('#form_users .tabela .caixa_corte #senha_inicial').click(function(){
        // abrirPopUp('addUsers.php',400,210);
        alert('O usuário ainda não alterou a senha inicial!');
    });
    $('#form_users .tabela .caixa_corte #alt_senha').click(function(){
        // abrirPopUp('altUser.php',400,210);
    });
    $('#form_users .tabela .caixa_corte #editar').click(function(){
        // $('#janela_edit_user').show();
        var usr_id = $(this).attr("usr_id");
        $.ajax({
            method: "GET",
            dataType : "json",
            url: "consultaUser.php",
            data: "id="+usr_id,
            success: function(json){
                if (json.error==1) {
                    alert(json.mensagem);
                } else {
                    var id = json.id;
                    var nome = json.nome;
                    var email = json.email;
                    var nivel = json.nivel;
                    var ativo = json.ativo;
                    $("#janela_edit_user #form_editUsers #id").attr("value",id);
                    $("#janela_edit_user #form_editUsers #nome").attr("value",nome);
                    $("#janela_edit_user #form_editUsers #email").attr("value",email);
                    if (ativo == '0') {
                        $("#janela_edit_user #form_editUsers .user_on_off img").attr("class","usr_pas");
                    } else if (ativo == '1') {
                        $("#janela_edit_user #form_editUsers .user_on_off img").attr("class","usr_atv");
                    }
                    $("#janela_edit_user #form_editUsers .user_on_off img").attr("usr_id",id);
                    var radios = document.getElementsByName('nivel');
                    if (nivel==0) {
                        radios[0].checked = true;
                        $('#permispermis').html("<a href='#' id='permicoes' usr_id='"+id+"' onClick='linkPermicoes(this);'>Alterar permissões ▼</a>");
                    }
                    if (nivel==1) {
                        radios[1].checked = true;
                        $('#permispermis').text("");
                    }
                    $('#janela_edit_user').show();
                }
            }
        });
        // $('#janela_edit_user').show();
    });
    $('#janela_edit_user #fechar').click(function(){
        $("#janela_edit_user #form_editUsers #id").attr("value","");
        $("#janela_edit_user #form_editUsers #nome").attr("value","");
        $("#janela_edit_user #form_editUsers #email").attr("value","");
        var radios = document.getElementsByName('nivel');
        for (var i=0;i<radios.length;i++) {
            radios[i].checked = false;
        }
        $('#janela_edit_user').hide();
    });
    // $('#janela_edit_user #permicoes').click(function(){
    //     var user = $(this).attr("usr_id");
    //     abrirPopUp('permis.php?user='+user,400,550);
    // });
    $(document).keyup(function(e){
        if ($('body').attr('popup')=='sim' && e.which == 27) {
            window.close();
        }
    });
    $('#form_addUsers').submit(function(){
        //verifica se os dois campos estão preenchidos
        if ($('#nome').val()!="" && $('#email').val()!="" && $('#nivel').val()!="") {
            var valores = $('#form_addUsers').serialize();
            $.ajax({
                method: "GET",
                dataType : "json",
                url: "addUsersBd.php",
                data: valores+"&function=insert",
                beforeSend: function(){
                    $('#carregando').show();
                },
                success: function(json){
                    $('#carregando').hide();
                    if (json.autoriza=='1') {
                        window.opener.location.reload();
                        alert(json.mensagem);
                        window.close();
                    } else {
                        alert(json.mensagem);
                    }
                }
            });
        } else {
            alert('Preencha todos os campos...!');
        }
        return false;
    });
    $('#form_editUsers').submit(function(){
        //verifica se os dois campos estão preenchidos
        if ($('#nome').val()!="" && $('#email').val()!="" && $('#nivel').val()!="") {
            var valores = $('#form_editUsers').serialize();
            $.ajax({
                method: "GET",
                dataType : "json",
                url: "addUsersBd.php",
                data: valores+"&function=update",
                beforeSend: function(){
                    $('#carregando').show();
                },
                success: function(json){
                    $('#carregando').hide();
                    if (json.autoriza=='1') {
                        alert(json.mensagem);
                        location.reload();
                    } else {
                        alert(json.mensagem);
                    }
                }
            });
        } else {
            alert('Preencha todos os campos...!');
        }
        return false;
    });
    $("#primeiro_acesso form").submit(function(){
        var nome = $("#nome").val();
        var email = $("#email").val();
        var tok1 = $("#tok1").val();
        var tok2 = $("#tok2").val();
        if (nome!="" && email!="" && tok1!="" && tok2!="") {
            usuario = email.substring(0, email.indexOf("@"));
            dominio = email.substring(email.indexOf("@")+ 1, email.length);
            if ((usuario.length >=1) && (dominio.length >=3) && (usuario.search("@")==-1) && (dominio.search("@")==-1) && (usuario.search(" ")==-1) && (dominio.search(" ")==-1) && (dominio.search(".")!=-1) && (dominio.indexOf(".") >=1)&& (dominio.lastIndexOf(".") < dominio.length - 1)) {
                if (tok1 == tok2) {
                    if (tok2.length >= 5) {
                        if (tok2!="12345" && tok2!="root") {
                            form.submit();
                        } else {
                            alert('Senhas inválidas!');
                        }
                    } else {
                        alert('As senhas devem ter no mínimo 5 dígitos!');
                    }
                } else {
                    alert('Favor conferir se as senhas estão iguais!');
                }
            } else {
                alert('Email inválido!');
            }
        } else {
            alert("É necessário preencher todos os campos!");
        }
        return false;
    });
    $('#janela_edit_user .user_on_off img').click(function(){
        var usr_id = $(this).attr("usr_id");
        $.ajax({
            method: "GET",
            dataType : "json",
            url: "altSitUserBd.php",
            data: "id="+usr_id+"&function=update",
            success: function(json){
                if (json.autoriza =='0') {
                    alert(json.mensagem);
                } else if (json.autoriza =='1') {
                    if (json.sit_user == '0') {
                        $("#janela_edit_user #form_editUsers .user_on_off img").attr("class","usr_pas");
                    } else if (json.sit_user == '1') {
                        $("#janela_edit_user #form_editUsers .user_on_off img").attr("class","usr_atv");
                    }
                }
            }
        });
    });
    $("#tela_adc_ped #fechar").click(function(){
        // $("#box_proc_cli").hide();
        $("#box_proc_cli").fadeOut(400);
        // $("#fundo_fumace").hide();
        $("#fundo_fumace").fadeOut(400);
        linWhite("tabela_itens");
        desselecionar();
        limpa_ped();
        $("#adc_ped_body #finalizar").attr('ativo',false);
        $("#adc_ped_body #finalizar").attr('class','botaofalse');
    });
    $("#adc_ped_body #proc_cli").click(function(){
        carregaHtml("buscaClientes.php","#box_proc_cli #box_body",funcoes_box_proc_cli);
        // $("#box_proc_cli").show();
        $("#box_proc_cli").fadeIn(400);
    });
    $("#box_proc_cli #fechar_proc_cli").click(function(){
        // $("#box_proc_cli").hide();
        $("#box_proc_cli").fadeOut(400);
    });
    $("#box_proc_cli #atualizar_proc_cli").click(function(){
        carregaHtml("buscaClientes.php","#box_proc_cli #box_body",funcoes_box_proc_cli);
    });
    $("#adc_ped_body .cod_cli").blur(function(){
        if ($("#adc_ped_body .cod_cli").val()!="") {
            var cod = $("#adc_ped_body .cod_cli").val();
            $.ajax({
                method: "GET",
                dataType : "json",
                url: "buscaClientesCod.php",
                data: "id="+cod,
                success: function(json) {
                    if (json.error==1) {
                        alert(json.mensagem);
                        $("#adc_ped_body .nome_cli").val('');
                        $("#adc_ped_body .login_cli").val('');
                        $("#adc_ped_body .cod_cli").focus();
                    } else if (json.error==0) {
                        $("#adc_ped_body .cod_cli").val(json.id);
                        $("#adc_ped_body .nome_cli").val(json.nome);
                        $("#adc_ped_body .login_cli").val(json.email);
                    } else {
                        alert("Erro sem tratamento. Favor contactar o administrador do sistema.");
                            $("#adc_ped_body .nome_cli").val('');
                            $("#adc_ped_body .login_cli").val('');
                            $("#adc_ped_body .cod_cli").focus();
                    }
                }
            });
        } else {
            $("#adc_ped_body .cod_cli").val('');
            $("#adc_ped_body .nome_cli").val('');
            $("#adc_ped_body .login_cli").val('');
        }
    });
    $("#adc_ped_body #adc_lin").click(function(){
        $("#box_edit_item").show();
        $("#box_edit_item #cod_item").focus();
    });
    $("#box_edit_item #box_rodape #fechar_edit_cli").click(function(){
        $("#box_edit_item").hide();
        $("#box_edit_item #cod_item").val("");
        $("#box_edit_item #cod_item").css("border","");
        for (var itab=1;itab<=7;itab++) {
            $("#box_edit_item #tab"+itab).val("");
            $("#box_edit_item #tab"+itab).css("border","");
        }
    });
    $("#box_edit_item #box_rodape #salvar").click(function(){
        var tab0 = $("#box_edit_item #cod_item").val();
        var tab1 = $("#box_edit_item #tab1").val();
        var tab2 = $("#box_edit_item #tab2").val();
        var tab3 = parseFloat($("#box_edit_item #tab3").val().replace(",","."));
        var tab4 = parseFloat($("#box_edit_item #tab4").val().replace(",","."));
        if ($("#box_edit_item #tab5").val()=="") $("#box_edit_item #tab5").val("0");
        if ($("#box_edit_item #tab6").val()=="") $("#box_edit_item #tab6").val("0");
        var tab5 = parseFloat($("#box_edit_item #tab5").val().replace(",","."));
        var tab6 = parseFloat($("#box_edit_item #tab6").val().replace(",","."));
        var tab7 = $("#box_edit_item #tab7").val();
        var tab8 = $("#box_edit_item #tab8").val();
        var tab9 = subTotal(tab3,tab4,tab5,tab6);
        
        var camposnulos = 0;
        if (tab0=="") {
            $("#box_edit_item #cod_item").css("border","1px solid rgb(255,0,0)");
            camposnulos = 1;
        } else {
            $("#box_edit_item #cod_item").css("border","");
        }
        for (var ii=1;ii<7;ii++) {
            if ($("#box_edit_item #tab"+ii).val()=="") {
                $("#box_edit_item #tab"+ii).css("border","1px solid rgb(255,0,0)");
                camposnulos = 1;
            } else {
                $("#box_edit_item #tab"+ii).css("border","");
            }
        }
        var datainput = document.getElementById("tab7")
        var ret = fctValidaData(datainput);
        if (!ret) {
            $("#box_edit_item #tab7").css("border","1px solid rgb(255,0,0)");
            camposnulos = 1;
        } else {
            $("#box_edit_item #tab7").css("border","");
        }
        
        if (camposnulos==0) {
            // Captura a referência da tabela com id “minhaTabela”
            var table = document.getElementById("tabela_itens");
            // Captura a quantidade de linhas já existentes na tabela
            var numOfRows = table.rows.length;
            contLinhas++;
            
            if (numOfRows>0) {
                // Captura a quantidade de colunas da última linha
                var numOfCols = table.rows[numOfRows-1].cells.length;
                // Insere uma linha no fim da tabela.
                var newRow = table.insertRow(numOfRows);
                    newRow.setAttribute("id","lin"+contLinhas);
                    newRow.setAttribute("lin",contLinhas);
                // Faz um loop para criar as colunas
                for (var i=0;i<numOfCols;i++) {
                    // Insere uma coluna na nova linha 
                    newCell = newRow.insertCell(i);
                    newCell.setAttribute("id","l"+contLinhas+"c"+(i+1));
                    //largura celulas
                    if (i==0) newCell.width = "62px";
                    if (i==1) newCell.width = "250px";
                    if (i==2) newCell.width = "30px";
                    if (i==3) newCell.width = "70px";
                    if (i==4) newCell.width = "60px";
                    if (i==5) newCell.width = "45px";
                    if (i==6) newCell.width = "45px";
                    if (i==7) newCell.width = "80px";
                    if (i==8) newCell.width = "80px";
                    if (i==9) newCell.width = "100px";
                    //classes
                    if (i==0) newCell.className = "alfa";
                    if (i==2) newCell.className = "al_ce";
                    if (i==3||i==4||i==5||i==6||i==7||i==8) newCell.className = "al_ri";
                    if (i==9) newCell.className = "omega";
                    //valores
                    if (i==0) newCell.innerHTML = tab0;
                    if (i==1) newCell.innerHTML = tab1;
                    if (i==2) newCell.innerHTML = tab2;
                    if (i==3) newCell.innerHTML = tab3.toFixed(2);
                    if (i==4) newCell.innerHTML = tab4.toFixed(2);
                    if (i==5) newCell.innerHTML = tab5.toFixed(2);
                    if (i==6) newCell.innerHTML = tab6.toFixed(2);
                    if (i==7) newCell.innerHTML = tab7;
                    if (i==8) newCell.innerHTML = tab8;
                    if (i==9) newCell.innerHTML = tab9.toFixed(2);
                }
            } else {
                var newRow = table.insertRow(0);
                    newRow.setAttribute("id","lin"+contLinhas);
                    newRow.setAttribute("lin",contLinhas);
                for (var i=0;i<10;i++) {
                    newCell = newRow.insertCell(i);
                    newCell.setAttribute("id","l"+contLinhas+"c"+(i+1));
                    //largura celulas
                    if (i==0) newCell.width = "62px";
                    if (i==1) newCell.width = "250px";
                    if (i==2) newCell.width = "30px";
                    if (i==3) newCell.width = "70px";
                    if (i==4) newCell.width = "60px";
                    if (i==5) newCell.width = "45px";
                    if (i==6) newCell.width = "45px";
                    if (i==7) newCell.width = "80px";
                    if (i==8) newCell.width = "80px";
                    if (i==9) newCell.width = "100px";
                    //classes
                    if (i==0) newCell.className = "alfa";
                    if (i==2) newCell.className = "al_ce";
                    if (i==3||i==4||i==5||i==6||i==7||i==8) newCell.className = "al_ri";
                    if (i==9) newCell.className = "omega";
                    //valores
                    if (i==0) newCell.innerHTML = tab0;
                    if (i==1) newCell.innerHTML = tab1;
                    if (i==2) newCell.innerHTML = tab2;
                    if (i==3) newCell.innerHTML = tab3.toFixed(2);
                    if (i==4) newCell.innerHTML = tab4.toFixed(2);
                    if (i==5) newCell.innerHTML = tab5.toFixed(2);
                    if (i==6) newCell.innerHTML = tab6.toFixed(2);
                    if (i==7) newCell.innerHTML = tab7;
                    if (i==8) newCell.innerHTML = tab8;
                    if (i==9) newCell.innerHTML = tab9.toFixed(2);
                }
            }
            $("#box_edit_item").hide();
            $("#box_edit_item #cod_item").val("");
            $("#box_edit_item #tab1").val("");
            $("#box_edit_item #tab2").val("");
            $("#box_edit_item #tab3").val("");
            $("#box_edit_item #tab4").val("");
            $("#box_edit_item #tab5").val("");
            $("#box_edit_item #tab6").val("");
            $("#box_edit_item #tab7").val("");
            $("#box_edit_item #tab8").val("");
            $("#box_edit_item #subsub").text("");
        }
        $("#tabela_itens #lin"+contLinhas).click(function(){
            if ($(this).attr("bgcolor")==""||$(this).attr("bgcolor")==undefined) ativo = false; else ativo = true;
            var iddd = $(this).attr("lin");
            linWhite("tabela_itens");
            if (!ativo) {
                document.getElementById("lin"+iddd).setAttribute("bgcolor","#ECF6CE");
                selecionar(iddd);
            } else {
                desselecionar();
            }
        });
    });
    $("#box_edit_item #tab3").blur(function(){
        exibeSubsub();
    });
    $("#box_edit_item #tab4").blur(function(){
        exibeSubsub();
    });
    $("#box_edit_item #tab5").blur(function(){
        exibeSubsub();
        if ($(this).val()=="") $(this).val("0");
    });
    $("#box_edit_item #tab6").blur(function(){
        exibeSubsub();
        if ($(this).val()=="") $(this).val("0");
    });
    $("#box_edit_item #tab7").blur(function(){
        var retorno = fctValidaData(this);
        if (!retorno) {
            $("#box_edit_item #tab7").css("border","1px solid rgb(255,0,0)");
        } else {
            $("#box_edit_item #tab7").css("border","");
        }
    });
    $("#box_edit_item #tab7").keyup(function(){
        var campodata = $(this).val();
        if (campodata.length==2||campodata.length==5) {
            var conteudo = campodata+"/";
            $(this).val(conteudo);
        }
    });
    $("#box_edit_item #cod_item").blur(function(){
        var campo = $("#box_edit_item #cod_item");
        if (campo.val()!=""&&isNaN(campo.val())) {
            $("#box_edit_item #cod_item_span").text("*Somente números!");
        } else {
            $("#box_edit_item #cod_item_span").text("");
        }
    });
    $("#caixa_com_itn #rem_lin").click(function(){
        if (linhaAtv!=0) {
            excluiLin("tabela_itens",linhaAtv);
        }
    });
    $("#caixa_fin #gravar").click(function(){
        linWhite("tabela_itens");
        desselecionar();
        gravarPedido();
    });
    $("#caixa_fin #limpar").click(function(){
        limpa_ped();
    });
    $("#form_pedidos #tabela_pedidos tbody tr").click(function(){
        var linha = $(this);
        if (linha.attr("bgcolor")===""||linha.attr("bgcolor")===undefined) ativo = false; else ativo = true;
        linWhite("tabela_pedidos");
        if (!ativo) {
            linha.attr("bgcolor","#ECF6CE");
            selecionar(linha);
        } else {
            desselecionar();
        }
    });
    $("#form_pedidos #adc_ped").click(function(){
        limpa_ped();
        // $("#fundo_fumace").show();
        $("#fundo_fumace").fadeIn(400);
        $("#tela_adc_ped #adc_ped_body #cod_ped").focus();
        linWhite("tabela_pedidos");
        desselecionar();
    });
    $("#form_pedidos #rem_ped").click(function(){
        removePed();
    });
    $("#tela_adc_ped #finalizar").click(function(){
        finalizaPedido();
    });
    $("#mostrar_dados_conf").click(function(){
        $("#linps1").slideUp("slow",function(){
            $("#linps2").slideDown("slow",function(){
                $("#linps3").slideDown("slow");
            });
        });
        return false;
    });
}