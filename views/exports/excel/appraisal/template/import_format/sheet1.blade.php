<table>
  <tr>
    <td style="font-size: 18;"><b>IMPORT APPRAISAL</b></td>
  </tr>
</table>

<table>
  <tr>
    <td align="center" width="20" valign="center" rowspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>TITLE<span style="color: red">*</span></b>
    </td>
    <td align="center" width="20" valign="center" rowspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>PERIOD YEAR<span style="color: red">*</span></b>
    </td>
    <td align="center" width="20" valign="center" rowspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>PERIOD SMT<span style="color: red">*</span></b>
    </td>
    <td align="center" width="20" valign="center" colspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>DIVISION<span style="color: red">*</span></b>
    </td>
    <td align="center" width="20" valign="center" rowspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>IS LOCKED<span style="color: red">*</span></b>
    </td>
    <td align="center" width="20" valign="center" rowspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>IS ARCHIVED<span style="color: red">*</span></b>
    </td>
    <td align="center" width="20" valign="center" rowspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>DESCRIPTION<span style="color: red">*</span></b>
    </td>
    <td align="center" width="20" valign="center" colspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>CATEGORY<span style="color: red">*</span></b>
    </td>
    <td align="center" width="20" valign="center" rowspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>GROUP KPI<span style="color: red">*</span></b>
    </td>
    <td align="center" width="20" valign="center" rowspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>QUESTION<span style="color: red">*</span></b>
    </td>
    <td align="center" width="20" valign="center" rowspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>FORMULA<span style="color: red">*</span></b>
    </td>
    <td align="center" width="20" valign="center" rowspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>WEIGHT<span style="color: red">*</span></b>
    </td>
  </tr>

  <tr>
    <td align="center" width="20" valign="center" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>ID</b>
    </td>
    <td align="center" width="30" valign="center" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>NAME</b>
    </td>
    <td align="center" width="20" valign="center" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>ID</b>
    </td>
    <td align="center" width="30" valign="center" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>NAME</b>
    </td>
  </tr>

  @for ($i = 0; $i < 20; $i++)
    <tr>
      <td></td> <!-- 1 -->
      <td></td> <!-- 2 -->
      <td></td> <!-- 3 -->
      <td></td>
      <!-- 4 -->
      <td>=VLOOKUP(D{{ $i + 5 }},DIVISION!$A$3:$B${{ $divisions->count() + 3 }}, 2, FALSE)</td>
      <!-- 5 -->
      <td></td> <!-- 6 -->
      <td></td>
      <!-- 7 -->
      <td></td> <!-- 8 -->
      <td></td> <!-- 9 -->
      <td>=VLOOKUP(I{{ $i + 5 }},CATEGORY!$A$3:$B${{ $categories->count() + 3 }}, 2, FALSE)</td> <!-- 10 -->
      <td></td> <!-- 11 -->
      <td></td> <!-- 12 -->
      <td></td> <!-- 13 -->
      <td></td> <!-- 14 -->
    </tr>
  @endfor

</table>
