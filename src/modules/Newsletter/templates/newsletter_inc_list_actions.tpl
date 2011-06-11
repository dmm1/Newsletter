        {ml assign="lblDetail"    name="View Details"}
        {ml assign="lblEdit"      name="Edit"}
        {ml assign="lblDelete"    name="Delete"}
        {formutil_getpassedvalue name="type" assign="curtype" default="user"}
	{securityutil_checkpermission assign="adminAuth" component="Newsletter::" instance="::" level="ACCESS_ADMIN"}
	<td class="nowrap" align="right">
          {if ($adminAuth)}
		    <a href="{modurl modname="Newsletter" type="admin" func="detail" ot=$ot id=$currentObject.id}">{img src='documentinfo.png' modname='core' set='icons/extrasmall' __alt=$lblDetail altml='false' __title=$lblDetail titleml='false'}</a>
            <a href="{modurl modname="Newsletter" type="admin" func="edit" ot=$ot id=$currentObject.id}">{img src='xedit.png' modname='core' set='icons/extrasmall' __alt=$lblEdit altml='false' __title=$lblEdit titleml='false'}</a>
            <a href="{modurl modname="Newsletter" type="admin" func="delete" ot=$ot1 id=$currentObject.id}">{img src='cancel.png' modname='core' set='icons/extrasmall' __alt=$lblDelete altml='false' __title=$lblDelete titleml='false'}</a>
          {/if}
        </td>
