package cz.kle.metro;

import android.os.Bundle;
import org.apache.cordova.*;

public class Metro extends CordovaActivity 
{
    @Override
    public void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        super.init();
        super.loadUrl(Config.getStartUrl());
    }
}