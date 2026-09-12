<table>
  <tr>
    <td style="font-size: 18;"><b>IMPORT EMPLOYEE CAREER</b></td>
  </tr>
  <tr>
    <td style="font-size: 12;"><i>*Format Date: YYYY-MM-DD*</i></td>
  </tr>
</table>

<table>
  <tr>
    <td align="center" width="20" valign="center" colspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>CAREER<span style="color: red">*</span></b>
    </td>
    <td align="center" width="20" valign="center" colspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>PLACEMENT<span style="color: red">*</span></b>
    </td>
    <td align="center" width="20" valign="center" colspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>ORGANIZATION<span style="color: red">*</span></b>
    </td>
    <td align="center" width="20" valign="center" rowspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>DATE<span style="color: red">*</span></b>
    </td>
    <td align="center" width="20" valign="center" rowspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
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
    <td align="center" width="20" valign="center" bgcolor="#EEEEEE" style="border: 1px solid #666" height="30">
      <b>ID</b>
    </td>
    <td align="center" width="30" valign="center" bgcolor="#EEEEEE" style="border: 1px solid #666" height="30">
      <b>NAME</b>
    </td>
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
      <td>=VLOOKUP(A{{ $i + 6 }},CAREER!$A$3:$B${{ $careers->count() + 3 }}, 2, FALSE)</td>
      <td></td>
      <td>=VLOOKUP(C{{ $i + 6 }},PLACEMENT!$A$3:$B${{ $placements->count() + 3 }}, 2, FALSE)</td>
      <td></td>
      <td>=VLOOKUP(E{{ $i + 6 }},ORGANIZATION!$A$3:$B${{ $organizations->count() + 3 }}, 2, FALSE)</td>
      <td></td>
      <td></td>
    </tr>
  @endfor

</table>
