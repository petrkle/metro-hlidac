package cz.kle.metro;

import android.os.Bundle;
import org.apache.cordova.*;

public class Metro extends CordovaActivity 
{
    @Override
    public void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
				Bundle extras = getIntent().getExtras();
        if (extras != null && extras.getBoolean("cdvStartInBackground", false)) {
            moveTaskToBack(true);
        }
        loadUrl(launchUrl);
    }
}
