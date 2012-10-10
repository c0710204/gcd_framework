<?php if (@!$gbSkipHeaderFooter) { ?>
				<p>&nbsp;</p>			
			<!-- right column (end) -->
			<?php if (isset($gTimer)) $gTimer->Stop() ?>
	    </td>	
		</tr>
	</table>
	<!-- content (end) -->	
	<!-- footer (begin) --><!-- *** Note: Only licensed users are allowed to remove or change the following copyright statement. *** -->

	<!-- footer (end) -->	
</div>
<?php } ?>
<div class="yui-tt" id="ewTooltipDiv" style="visibility: hidden; border: 0px;"></div>
<script type="text/javascript">
ew_Select("table." + EW_TABLE_CLASSNAME, document, ew_SetupTable); // Init tables
ew_Select("table." + EW_GRID_CLASSNAME, document, ew_SetupGrid); // Init grids
ew_InitTooltipDiv(); // init tooltip div
</script>
<script type="text/javascript">

// Write your global startup script here
// document.write("page loaded");

</script>
</body>
</html>
