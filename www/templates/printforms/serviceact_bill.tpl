<table class="ctable"   border="0" cellpadding="1" cellspacing="0"  {{{printw}}}  >
    <tr>
        <td colspan="2">Квитанция {{document_number}}</td>
    </tr>
    <tr>

        <td colspan="2">от {{date}}</td>
    </tr>
    <tr>

        <td colspan="2"> {{firmname}}</td>
    </tr>
    {{#shopname}}
    <tr>
        <td colspan="2"> {{shopname}}</td>
    </tr>
      {{/shopname}}
    <tr>

        <td colspan="2">  {{address}}</td>
    </tr>
    <tr>
        <td colspan="2"> {{phone}}</td>
    </tr>
     
    <tr>
        <td colspan="2"> {{customer_name}}</td>
    </tr>
 


    {{#isdevice}}
    <tr>
        <td colspan="2"> Принято от клиента</td>
    </tr>
    <tr>
        <td colspan="2"> {{device}}</td>
    </tr>
    {{/isdevice}}
    <tr>
        <td colspan="2">Работы:</td>
    </tr>
    {{#slist}}
    <tr>
        <td  >{{service}}</td>
        <td align="right" >{{price}}</td>
    </tr>

    {{/slist}}
    <tr>
        <td align="right">Всего:</td>
        <td align="right" >{{total}}</td>
    </tr>
    {{#ispay}} 
    <tr>
        <td colspan="2">Оплаты:</td>
    </tr>    
    {{#plist}}
    <tr>
        <td  >{{pdate}}</td>
        <td align="right" >{{ppay}}</td>
    </tr>

    {{/plist}}  
    {{/ispay}} 
    <tr>
        <td colspan="2"> {{gar}}</td>

    </tr>     
    <tr>
        <td  >Исполнитель</td>
        <td  >________</td>
    </tr>     
    <tr>
        <td  >Клиент</td>
        <td  >________</td>
    </tr>     
</table>