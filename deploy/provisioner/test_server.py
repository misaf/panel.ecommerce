import base64
import json
import tempfile
import unittest
from pathlib import Path

from server import ProvisioningError, upsert_env, validate_configuration


class ProvisionerTest(unittest.TestCase):
    def configuration(self, **overrides: str) -> str:
        configuration = {
            "slug": "acme-flowers",
            "domain": "acme.example.com",
            "theme": "default",
            **overrides,
        }
        return base64.b64encode(json.dumps(configuration).encode()).decode()

    def test_validates_runtime_configuration_identity(self) -> None:
        validate_configuration(
            self.configuration(), "acme-flowers", "acme.example.com"
        )

        with self.assertRaises(ProvisioningError):
            validate_configuration(
                self.configuration(domain="other.example.com"),
                "acme-flowers",
                "acme.example.com",
            )

    def test_upserts_generated_environment_without_losing_other_values(self) -> None:
        with tempfile.TemporaryDirectory() as directory:
            path = Path(directory) / ".env"
            path.write_text("DOMAIN=old.example.com\nBASE_DOMAIN=vendra.test\n")

            upsert_env(
                path,
                {
                    "DOMAIN": "acme.example.com",
                    "STOREFRONT_CONFIG_BASE64": "encoded",
                },
            )

            self.assertEqual(
                path.read_text(),
                "DOMAIN=acme.example.com\nBASE_DOMAIN=vendra.test\n\n"
                "STOREFRONT_CONFIG_BASE64=encoded\n",
            )


if __name__ == "__main__":
    unittest.main()
