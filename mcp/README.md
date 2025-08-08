# Mint MCP

Mint MCP is a module in Mint that acts as an MCP server and enables integration with MCP clients such as Visual Studio Code, Copilot Chat, etc. Thanks to this, you can use MCP tools in Mint and add your own.

## Server Configuration

### Adding a New Domain for the Mint MCP Server

To use Mint MCP, you need to configure a new domain that will be used by the MCP server, for example, `https://your-mcp-domain`. This domain should point to the `mcp` directory in your Mint instance, e.g., `/var/www/MintHCM/mcp`.

With this configuration, the Mint MCP server will be accessible at `https://your-mcp-domain` and will be able to authenticate users using OAuth 2.1, as required by the MCP specification.

## MCP Client Configuration

The following example describes configuration for the MCP client in Visual Studio Code; it should be similar for other clients.

### Adding MCP Server in Visual Studio Code
1. Run command `MCP: Add Server...` in Visual Studio Code.
2. Choose HTTP as the server type.
1. Enter the URL of your Mint MCP server, e.g., `https://your-mcp-domain`.
1. Enter a name for the server, e.g., `my-mcp-server-mint1`.
Your configuration should look like this in your `mcp.json` file:

```json
{
    "servers": {
        "my-mcp-server-mint1": {
            "type": "http",
            "url": "https://your-mcp-domain"
        }
    }
}
```

### Using the MCP Server in Visual Studio Code
1. Run the command `MCP: List Servers` and select your server `my-mcp-server-mint1`.
1. Select `Start server`. If this is your first time using Mint MCP via this client, you will be asked to login to your MintHCM. After succesful login wait for the message `Connection state: Running` and then `Discovered X tools`.
1. Open Copilot Chat, set the mode to `Agent`, and select `"Configure tools..."` (the tools icon under the chat input field).
1. Make sure that in the `my-mcp-server-mint1` section, the tools you want to use are checked.

After completing these steps, your MCP client should be properly configured and ready to use in.