import "./style.css";
import {
  App,
  applyDocumentTheme,
  applyHostFonts,
  applyHostStyleVariables,
  type McpUiHostContext,
} from "@modelcontextprotocol/ext-apps";

type SearchArgs = {
  module_name?: string;
  fields?: string[];
  filters?: string;
  operator?: string;
  offset?: number;
  limit?: number;
};

type SearchStructuredContent = {
  records_returned?: number;
  data?: Record<string, unknown>[];
  pagination_info?: {
    message?: string;
    total_count?: number;
    current_offset?: number;
    records_returned?: number;
    next_offset?: number;
  } | null;
};

const TOOL_NAME = "search_records";

const subtitleEl = document.getElementById("subtitle") as HTMLParagraphElement;
const statusCardEl = document.getElementById("status-card") as HTMLDivElement;
const statusMessageEl = document.getElementById("status-message") as HTMLParagraphElement;
const metaEl = document.getElementById("results-meta") as HTMLDivElement;
const tableEl = document.getElementById("results-table") as HTMLDivElement;

const moduleNameInput = document.getElementById("module-name") as HTMLInputElement;
const fieldsInput = document.getElementById("fields") as HTMLInputElement;
const filtersInput = document.getElementById("filters") as HTMLInputElement;
const operatorInput = document.getElementById("operator") as HTMLInputElement;
const offsetInput = document.getElementById("offset") as HTMLInputElement;
const limitInput = document.getElementById("limit") as HTMLInputElement;
const loadRecordsBtn = document.getElementById("load-records-btn") as HTMLButtonElement;

const app = new App({ name: "Search Records App", version: "1.0.0" });
let lastToolArgs: SearchArgs | null = null;
let fallbackTimer: ReturnType<typeof setTimeout> | null = null;
let resultSeen = false;

function setLoading(isLoading: boolean, message = "Loading records..."): void {
  if (isLoading) {
    statusMessageEl.textContent = message;
    statusCardEl.classList.remove("search-records-app__status--hidden");
  } else {
    statusCardEl.classList.add("search-records-app__status--hidden");
  }
}

function setSubtitle(text: string): void {
  subtitleEl.textContent = text;
}

function parseToolResult(result: unknown): SearchStructuredContent {
  const response = result as {
    structuredContent?: SearchStructuredContent;
    content?: Array<{ type?: string; text?: string }>;
  };
  if (response.structuredContent && typeof response.structuredContent === "object") {
    return response.structuredContent;
  }
  const textItem = response.content?.find((item) => item.type === "text" && typeof item.text === "string");
  if (textItem?.text) {
    try {
      return JSON.parse(textItem.text) as SearchStructuredContent;
    } catch {
      return { data: [] };
    }
  }
  return { data: [] };
}

function normalizeData(payload: SearchStructuredContent, args: SearchArgs | null) {
  const records: Record<string, unknown>[] = payload.data ?? [];
  const moduleName = args?.module_name ?? "";
  let columns: string[] = [];
  if (records[0]) {
    columns = Object.keys(records[0]).filter((key) => key !== "url");
  }
  return {
    moduleName,
    columns,
    records,
    paginationInfo: payload.pagination_info ?? null,
  };
}

function formatCell(value: unknown): string {
  if (value === null || value === undefined) return "";
  return String(value);
}

function renderResults(result: unknown, args: SearchArgs | null): void {
  const payload = parseToolResult(result);
  const { moduleName, columns, records, paginationInfo } = normalizeData(payload, args);
  const effectiveColumns = columns.filter((column) => column !== "id");
  const displayColumns = effectiveColumns.length ? effectiveColumns : ["id"];
  const linkColumn = displayColumns.includes("name") ? "name" : displayColumns[0];
  const remainingColumns = displayColumns.filter((column) => column !== linkColumn);

  setSubtitle(moduleName ? `Search results for ${moduleName}` : "Search results");
  metaEl.textContent = "";
  tableEl.replaceChildren();

  if (!records.length || !displayColumns.length) {
    metaEl.textContent = "No records found.";
    return;
  }

  const metaBits: string[] = [];
  if (paginationInfo?.records_returned !== undefined) {
    metaBits.push(`Showing ${paginationInfo.records_returned} record(s)`);
  }
  if (paginationInfo?.total_count !== undefined) {
    metaBits.push(`Total ${paginationInfo.total_count}`);
  }
  metaEl.textContent = metaBits.join(" | ");

  const table = document.createElement("table");
  table.className = "search-records-table";
  const thead = document.createElement("thead");
  const headerRow = document.createElement("tr");

  const recordTh = document.createElement("th");
  recordTh.textContent = "Record";
  headerRow.appendChild(recordTh);
  for (const column of remainingColumns) {
    const th = document.createElement("th");
    th.textContent = column.replaceAll("_", " ");
    headerRow.appendChild(th);
  }
  thead.appendChild(headerRow);
  table.appendChild(thead);

  const tbody = document.createElement("tbody");
  records.forEach((record) => {
    const tr = document.createElement("tr");
    const rowRecord = record as Record<string, unknown>;
    const displayName = formatCell(rowRecord[linkColumn] ?? rowRecord.id);
    const url = rowRecord.url as string | undefined;

    const recordCell = document.createElement("td");
    if (url) {
      const link = document.createElement("a");
      link.href = url;
      link.target = "_blank";
      link.rel = "noopener noreferrer";
      link.textContent = displayName;
      recordCell.appendChild(link);
    } else {
      recordCell.textContent = displayName;
    }
    tr.appendChild(recordCell);

    remainingColumns.forEach((column) => {
      const td = document.createElement("td");
      td.textContent = formatCell(rowRecord[column]);
      tr.appendChild(td);
    });

    tbody.appendChild(tr);
  });

  table.appendChild(tbody);
  tableEl.appendChild(table);
}

function syncControls(args: SearchArgs): void {
  if (args.module_name && !moduleNameInput.value) moduleNameInput.value = args.module_name;
  if (Array.isArray(args.fields) && !fieldsInput.value) fieldsInput.value = args.fields.join(",");
  if (args.filters && !filtersInput.value) filtersInput.value = args.filters;
  if (args.operator && !operatorInput.value) operatorInput.value = args.operator;
  if (typeof args.offset === "number" && !offsetInput.value) offsetInput.value = String(args.offset);
  if (typeof args.limit === "number" && !limitInput.value) limitInput.value = String(args.limit);
}

function buildArgsFromForm(): SearchArgs {
  const moduleName = moduleNameInput.value.trim();
  const fieldsRaw = fieldsInput.value.trim();
  if (!moduleName) {
    throw new Error("module_name is required");
  }
  if (!fieldsRaw) {
    throw new Error("fields is required");
  }
  const args: SearchArgs = {
    module_name: moduleName,
    fields: fieldsRaw
      .split(",")
      .map((item) => item.trim())
      .filter(Boolean),
  };
  if (filtersInput.value.trim()) args.filters = filtersInput.value.trim();
  if (operatorInput.value.trim()) args.operator = operatorInput.value.trim();
  if (offsetInput.value.trim()) args.offset = Number(offsetInput.value.trim());
  if (limitInput.value.trim()) args.limit = Number(limitInput.value.trim());
  return args;
}

function applyHostContext(ctx: McpUiHostContext): void {
  if (ctx.theme) applyDocumentTheme(ctx.theme);
  if (ctx.styles?.variables) applyHostStyleVariables(ctx.styles.variables);
  if (ctx.styles?.css?.fonts) applyHostFonts(ctx.styles.css.fonts);
}

app.ontoolinput = ({ arguments: args }) => {
  resultSeen = false;
  lastToolArgs = args as SearchArgs;
  syncControls(lastToolArgs);
  setSubtitle(`Running ${TOOL_NAME}...`);
  setLoading(true, "Loading records from host...");
  if (fallbackTimer) clearTimeout(fallbackTimer);
  fallbackTimer = setTimeout(async () => {
    if (resultSeen || !lastToolArgs) return;
    try {
      const fallbackResult = await app.callServerTool({ name: TOOL_NAME, arguments: lastToolArgs });
      resultSeen = true;
      renderResults(fallbackResult, lastToolArgs);
    } catch {
      metaEl.textContent = "Host did not forward tool result and fallback call failed.";
    } finally {
      setLoading(false);
      fallbackTimer = null;
    }
  }, 2500);
};

app.ontoolresult = (result) => {
  resultSeen = true;
  if (fallbackTimer) {
    clearTimeout(fallbackTimer);
    fallbackTimer = null;
  }
  setLoading(false);
  renderResults(result, lastToolArgs);
};

app.ontoolcancelled = () => {
  setLoading(false);
  metaEl.textContent = "Tool execution was cancelled by host.";
};

app.onhostcontextchanged = applyHostContext;
app.onerror = (error) => {
  setLoading(false);
  metaEl.textContent = error instanceof Error ? error.message : "Unexpected app error.";
};

loadRecordsBtn.addEventListener("click", async () => {
  try {
    const args = buildArgsFromForm();
    lastToolArgs = args;
    setLoading(true, "Loading records...");
    loadRecordsBtn.disabled = true;
    const result = await app.callServerTool({ name: TOOL_NAME, arguments: args });
    resultSeen = true;
    renderResults(result, args);
  } catch (error) {
    metaEl.textContent = error instanceof Error ? error.message : "Failed to load records.";
  } finally {
    setLoading(false);
    loadRecordsBtn.disabled = false;
  }
});

app
  .connect()
  .then(() => {
    const initialContext = app.getHostContext();
    if (initialContext) {
      applyHostContext(initialContext);
    }
    setSubtitle("Waiting for search_records input from host.");
  })
  .catch(() => {
    setSubtitle("Connected host does not support MCP Apps lifecycle.");
  });
