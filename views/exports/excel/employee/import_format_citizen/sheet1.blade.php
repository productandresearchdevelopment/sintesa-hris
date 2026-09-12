<table>
  <tr>
    <td style="font-size: 18;"><b>IMPORT EMPLOYEE CITIZEN</b></td>
  </tr>
  <tr>
    <td style="font-size: 12;"><i>*Format Date / Value: Text*</i></td>
  </tr>
</table>

<table>
  <tr>
    <td align="center" width="20" valign="center" colspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>CITIZEN<span style="color: red">*</span></b>
    </td>
    <td align="center" width="30" valign="center" rowspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>VALUE</b>
    </td>
    <td align="center" width="40" valign="center" rowspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>DESCRIPTION</b>
    </td>
  </tr>

  <tr>
    <td align="center" width="20" valign="center" bgcolor="#EEEEEE" style="border: 1px solid #666" height="30">
      <b>ID</b>
    </td>
    <td align="center" width="30" valign="center" bgcolor="#EEEEEE" style="border: 1px solid #666" height="30">
      <b>NAME</b>
    </td>
  </tr>

  @for ($i = 0; $i < 20; $i++)
    <tr>
      <td></td>
      <td>=VLOOKUP(A{{ $i + 6 }},CITIZEN!$A$3:$B${{ $citizens->count() + 3 }}, 2, FALSE)</td>
      <td></td>
      <td></td>
    </tr>
  @endfor

</table>
